<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function AdminDashboardPage()
    {
        // check set session
        if (!Auth::guard('employees')->check()) {
            return redirect()->route('login.page')->with('error', 'You must be logged in to access the dashboard.');
        }

        return view('admin.dashboard');
    }
}
