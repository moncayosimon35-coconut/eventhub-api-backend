<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $fillable = [
        'stripe_id',
        'amount',
        'currency',
        'status',
        'description',
    ];
}
