<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_firstname',
        'employee_lastname',
        'birthday',
        'position_id',
        'contract_id',
        'username',
        'password',
        'pin',
        'status',
        'login_attempts',
    ];
}
