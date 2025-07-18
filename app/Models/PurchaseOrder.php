<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'process_by',
        'approved_by',
        'product_name',
        'quantity',
        'price',
        'unit',
        'amount',
        'total_amount',
    ];
}
