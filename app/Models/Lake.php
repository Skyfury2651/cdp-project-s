<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Lake extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_bill',
        'id_bill',
        'id_bill_taken',
        'bill_order_time',
        'bill_delivery_time',
        'bill_group',
        'carer_code',
        'order_name',
        'line_code',
        'sold_time',
        'seller_name',
        'customer_code',
        'customer_name',
        'customer_group',
        'customer_type',
        'customer_address',
        'customer_phone',
        'customer_description',
        'warehouse_code',
        'product_code',
        'product_name',
        'unit',
        'quantity',
        'price',
        'amount',
        'vat_percent',
        'vat_number',
        'rebate',
        'bill_total',
        'tax_code',
        'channel',
        'data_source',
        'special_note',
    ];
}
