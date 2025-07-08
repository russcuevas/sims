<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function AdminUserManagementPage()
    {
        // check set session
        if (!Auth::guard('employees')->check()) {
            return redirect()->route('login.page')->with('error', 'You must be logged in to access the dashboard.');
        }

        return view('admin.user_management');
    }
}
