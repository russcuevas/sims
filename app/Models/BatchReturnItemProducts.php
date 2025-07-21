<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchReturnItemProducts extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'product_name',
        'stock_unit_id',
        'price',
        'category',
    ];
}
