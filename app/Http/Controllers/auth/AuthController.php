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
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $employee = \App\Models\Employee::where('username', $credentials['username'])->first();

        if ($employee) {
            if ($employee->status === 'Locked') {
                return back()->withErrors([
                    'username' => 'Your account is locked. Please contact the administrator or forgot your password',
                ]);
            }

            if (Auth::guard('employees')->attempt($credentials)) {
                if ($employee->is_archived) {
                    Auth::guard('employees')->logout();
                    return back()->withErrors(['username' => 'Username or password is incorrect.']);
                }

                $employee->update(['login_attempts' => 0]);
                $request->session()->regenerate();

                switch ($employee->position_id) {
                    case 1:
                        return redirect()->route('admin.dashboard.page');
                    case 2:
                        return redirect()->route('some.other.page');
                    case 3:
                        return redirect()->route('delivery.dashboard.page');
                    default:
                        return redirect()->route('default.page');
                }
            } else {
                $employee->increment('login_attempts');

                if ($employee->login_attempts + 1 >= 6) {
                    $employee->update(['status' => 'Locked']);
                    return back()->withErrors([
                        'username' => 'Your account has been locked due to too many failed login attempts. Please contact the administrator.',
                    ]);
                }

                return back()->withErrors([
                    'username' => 'Username or password is incorrect.',
                ]);
            }
        }

        return back()->withErrors([
            'username' => 'Username or password is incorrect.',
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
            'password' => 'required|confirmed|min:6',
        ]);

        $employee_id = Session::get('reset_employee_id');

        if (!$employee_id) {
            return redirect('/login')->withErrors(['session' => 'Session expired. Please request password reset again.']);
        }

        DB::table('employees')->where('id', $employee_id)->update([
            'password' => Hash::make($request->password),
            'login_attempts' => 0,
            'status' => 'Unlocked',
        ]);

        Session::forget('reset_employee_id');
        DB::table('change_passwords')->where('employee_id', $employee_id)->delete();

        return redirect('/login')->with('success', 'Your password has been updated.');
    }
}
