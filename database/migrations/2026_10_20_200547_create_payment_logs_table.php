<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_logs', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_id')->unique(); // ID del PaymentIntent (ch_... o pi_...)
            $table->decimal('amount', 10, 2);     // Monto en formato decimal (ej: 10.00)
            $table->string('currency', 3);        // Moneda (usd, eur)
            $table->string('status');              // Estado del pago (succeeded, failed)
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_logs');
    }
};
