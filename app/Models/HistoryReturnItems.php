<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryReturnItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'transact_id',
        'transaction_date',
        'process_by',
        'picked_up_by',
        'store_id',
        'product',
        'quantity',
        'unit',
        'price',
        'amount',
        'is_archived',
    ];
}
