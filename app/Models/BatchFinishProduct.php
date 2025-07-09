<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchFinishProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'quantity',
        'product_name',
        'stock_unit_id',
        'product_price',
    ];
}
