<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use Illuminate\Support\Facades\Log;
use App\Models\PaymentLog;

class PaymentController extends Controller
{
    /**
     * Muestra la vista del formulario de checkout.
     */
    public function showCheckout()
    {
        return view('checkout');
    }

    /**
     * Crear un PaymentIntent dinámico basado en el monto ingresado.
     */
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'description' => 'nullable|string|max:255',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Stripe procesa montos en unidades mínimas de la moneda (ej. centavos USD / EUR)
            $amountInCents = (int) round($request->amount * 100);

            $paymentIntent = PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => strtolower($request->currency),
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'metadata' => [
                    'description' => $request->description ?? 'Pago de prueba',
                ],
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Webhook para capturar la confirmación del pago.
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch (\UnexpectedValueException | SignatureVerificationException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
    $paymentIntent = $event->data->object;

    // GUARDAR EN BASE DE DATOS DE LOGS
    PaymentLog::create([
        'stripe_id'   => $paymentIntent->id,
        'amount'      => $paymentIntent->amount / 100, // Convertimos de centavos a decimal
        'currency'    => strtoupper($paymentIntent->currency),
        'status'      => 'succeeded',
        'description' => $paymentIntent->metadata->description ?? 'Pago vía Webhook',
    ]);
}

        return response()->json(['status' => 'success'], 200);
    }
}

