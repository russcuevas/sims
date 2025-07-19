<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryOrders extends Model
{
    use HasFactory;
    protected $fillable = [
        'transact_id',
        'memo',
        'transaction_date',
        'expected_delivery',
        'process_by',
        'approved_by',
        'delivered_by',
        'car',
        'store',
        'product_name',
        'pack',
        'unit',
        'quantity_ordered',
        'quantity_received',
        'price',
        'amount',
        'total_ordered',
        'total_amount',
        'status',
        'is_archived',
    ];
}
