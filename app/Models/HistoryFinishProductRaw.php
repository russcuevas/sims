<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryFinishProductRaw extends Model
{
    use HasFactory;

    protected $fillable = [
        'transact_id',
        'product_name',
        'current_quantity',
        'quantity',
        'unit'
    ];
}
