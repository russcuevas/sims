<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_date',
        'process_by',
        'transaction_type',
        'transaction_id',
        'payment',
        'return',
        'debit',
        'credit',
        'loss',
        'balances',
    ];
}
