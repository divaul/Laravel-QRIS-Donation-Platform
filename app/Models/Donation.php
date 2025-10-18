<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'order_id',
        'donor_name',
        'message',
        'amount',
        'payment_type',
        'transaction_status',
        'transaction_id',
        'qr_code',
        'acquirer',
        'transaction_time',
        'settlement_time',
        'raw_notification',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_time' => 'datetime',
        'settlement_time' => 'datetime',
        'raw_notification' => 'array',
    ];
}
