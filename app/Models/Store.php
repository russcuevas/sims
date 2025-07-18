<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_name',
        'store_code',
        'store_address',
        'store_tel_no',
        'store_cp_number',
        'store_fax',
        'store_tin',
    ];
}
