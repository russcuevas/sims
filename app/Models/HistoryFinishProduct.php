<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryFinishProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'transact_id',
        'product_name',
        'quantity',
        'unit',
        'price',
        'amount',
        'process_by',
        'process_date',
        'is_archived',
    ];
}
