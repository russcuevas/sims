<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function AdminDashboardPage()
    {
        // check set session
        if (!Auth::guard('employees')->check()) {
            return redirect()->route('login.page')->with('error', 'You must be logged in to access the dashboard.');
        }


        // fetching in left sidebar the users
        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

        return view('admin.dashboard', compact('role', 'user'));
    }
}
