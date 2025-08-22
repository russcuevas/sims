<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchFinishRawProducts extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'identity_no',
        'product_id_details',
        'product_name',
        'price',
        'quantity',
        'stock_unit_id',
        'product_price',
        'is_selected',
        'category',
    ];
}
