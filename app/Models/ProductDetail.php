<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_name',
        'price',
        'quantity',
        'stock_unit_id',
        'product_price',
        'category',
        'is_archived',
    ];
}
