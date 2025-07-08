<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function LoginPage()
    {
        return view('auth.login');
    }

    public function LoginRequest(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('employees')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard.page');
        }

        return back()->withErrors([
            'email' => 'Email or password is incorrect',
        ]);
    }


    public function LogoutRequest(Request $request)
    {
        Auth::guard('employees')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.page')->with('success', 'You have been logged out.');
    }



    public function ChangePasswordPage()
    {
        return view('auth.change_password');
    }
}
