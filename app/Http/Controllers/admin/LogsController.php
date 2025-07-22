<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogsController extends Controller
{
    public function AdminLogsPage()
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin to access the dashboard.');
        }

        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

        // Join activity_logs with employees to get employee full name (without pagination)
        $logs = DB::table('activity_logs')
            ->leftJoin('employees', 'activity_logs.employee_id', '=', 'employees.id')
            ->select(
                'activity_logs.*',
                DB::raw("CONCAT(employees.employee_firstname, ' ', employees.employee_lastname) as employee_name")
            )
            ->orderBy('activity_logs.created_at', 'desc')
            ->get(); // removed pagination

        // Fetch low quantity finish products
        $lowFinishedProducts = DB::table('product_details')
            ->where('category', 'finish product')
            ->where('quantity', '<', 1000)
            ->where('is_archived', 0)
            ->get();

        return view('admin.logs', compact('role', 'user', 'lowFinishedProducts', 'logs'));
    }
}
