<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'date_created',
        'order_id',
        'transaction_type',
        'channel',
        'status',
        'reference_id',
        'amount',
        'total_fee',
        'notes',
        'customer_name',
        'customer_email',
        'customer_mobile',
        'shipping_address',
        'transaction_time',
        'settlement_time',
        'expiry_time',
        'custom_field_1',
        'custom_field_2',
        'custom_field_3',
        'pop_name',
        'payment_provider_ref_id',
        'invoice_id',
        'subscription_id',
        'receiver_account_number',
        'sender',
        'receiver',
        'settlement_date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
