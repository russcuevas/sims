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
        'process_by_role',
        'approved_by',
        'delivered_by',
        'car',
        'store',
        'product_name',
        'pack',
        'unit',
        'quantity_ordered',
        'quantity_received',
        'quantity_returned',
        'payment_amount',
        'price',
        'amount',
        'total_ordered',
        'total_amount',
        'type_sign',
        'approved_by_assigned',
        'status',
        'is_archived',
    ];
}
