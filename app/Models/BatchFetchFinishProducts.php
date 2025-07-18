<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchFetchFinishProducts extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'product_id_details',
        'product_name',
        'unit',
        'price',
        'category',
    ];
}
