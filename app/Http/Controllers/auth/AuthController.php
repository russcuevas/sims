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

            $user = Auth::guard('employees')->user();

            switch ($user->position_id) {
                case 1:
                    return redirect()->route('admin.dashboard.page');
                case 2:
                    return redirect()->route('some.other.page'); // example for position 2
                case 3:
                    return redirect()->route('another.page'); // example for position 3
                default:
                    return redirect()->route('default.page'); // fallback route
            }
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
