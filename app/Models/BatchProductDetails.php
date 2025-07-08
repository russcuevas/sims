<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchProductDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'product_id',
        'product_name',
        'price',
        'quantity',
        'stock_unit_id',
        'product_price',
        'category',
    ];
}
