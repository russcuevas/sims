<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'product_id',
        'process_by',
        'approved_by',
        'product_name',
        'quantity',
        'price',
        'unit',
        'amount',
        'total_amount',
        'status'
    ];
}
