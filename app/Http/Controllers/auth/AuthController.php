<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\ChangePassword;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

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
                    return redirect()->route('some.other.page');
                case 3:
                    return redirect()->route('another.page');
                default:
                    return redirect()->route('default.page');
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

    public function ChangePasswordRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:employees,email',
        ]);

        $employee = DB::table('employees')->where('email', $request->email)->first();

        if (!$employee) {
            return back()->withErrors(['email' => 'Employee not found']);
        }

        $otp = rand(1000, 9999);
        $token = Str::random(64);
        $link = url("/reset-password/{$token}");

        DB::table('change_passwords')->insert([
            'employee_id' => $employee->id,
            'otp' => $otp,
            'link' => $token,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Mail::raw("Click here to reset your password: {$link}\n\nYour OTP is: {$otp}", function ($message) use ($employee) {
            $message->to($employee->email)
                ->subject('Password Reset Request');
        });

        return back()->with('success', 'A reset link and OTP have been sent to your email.');
    }

    public function VerifyOtp(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'otp' => 'required|digits:4',
        ]);

        $record = DB::table('change_passwords')
            ->where('link', $request->token)
            ->where('otp', $request->otp)
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'Invalid OTP']);
        }

        Session::put('reset_employee_id', $record->employee_id);
        return redirect('/reset-password-form');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $employee_id = Session::get('reset_employee_id');

        if (!$employee_id) {
            return redirect('/login')->withErrors(['session' => 'Session expired. Please request password reset again.']);
        }

        $employee = DB::table('employees')->where('id', $employee_id)->first();

        if (!$employee || !Hash::check($request->old_password, $employee->password)) {
            return back()->withErrors(['old_password' => 'The old password you entered is incorrect.']);
        }

        DB::table('employees')->where('id', $employee_id)->update([
            'password' => Hash::make($request->password),
        ]);

        Session::forget('reset_employee_id');
        DB::table('change_passwords')->where('employee_id', $employee_id)->delete();

        return redirect('/login')->with('success', 'Your password has been updated.');
    }
}
