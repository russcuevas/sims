<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class ActivityLogger
{
    public static function log($employeeId, $action, $module, $description = null)
    {
        DB::table('activity_logs')->insert([
            'employee_id' => $employeeId,
            'action'      => $action,
            'module'      => $module,
            'description' => $description,
            'created_at'  => now(),
        ]);
    }
}
