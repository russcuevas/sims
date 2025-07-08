<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'employee_firstname',
        'employee_lastname',
        'birthday',
        'position_id',
        'contract_id',
        'email',
        'username',
        'password',
        'pin',
        'status',
        'login_attempts',
        'is_archived',
    ];
}
