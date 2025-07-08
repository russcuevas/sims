<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryRawMaterials extends Model
{
    use HasFactory;

    protected $fillable = [
        'transact_id',
        'supplier_id',
        'product_id',
        'quantity',
        'unit',
        'price',
        'amount',
        'process_by',
        'received_date',
        'is_archived',
    ];
}
