<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;

class UserController extends Controller
{

    public function AdminUserManagementPage()
    {
        if (!Auth::guard('employees')->check()) {
            return redirect()->route('login.page')->with('error', 'You must be logged in to access the dashboard.');
        }

        $positions = DB::table('positions')->get();
        $contracts = DB::table('contracts')->get();

        $employees = DB::table('employees')
            ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
            ->leftJoin('contracts', 'employees.contract_id', '=', 'contracts.id')
            ->select(
                'employees.id',
                'employees.employee_firstname',
                'employees.employee_lastname',
                'positions.position_name',
                'contracts.contract',
                'employees.email',
                'employees.username',
                'employees.pin',
                'employees.login_attempts',
                'employees.status'
            )
            ->get()
            ->toArray();

        // fetching in the left sidebar the users
        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

        return view('admin.user_management', compact('positions', 'contracts', 'employees', 'role', 'user'));
    }


    public function AdminAddUser(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthday' => 'required|date',
            'role' => 'required|exists:positions,id',
            'contract' => 'required|exists:contracts,id',
            'email' => 'required|email|unique:employees,email',
            'username' => 'required|string|unique:employees,username|max:255',
            'pin' => ['required', 'digits:4'],
        ]);

        $formattedBirthday = str_replace('-', '', $request->birthday);
        $rawPassword = $request->last_name . $formattedBirthday;

        Employee::create([
            'employee_firstname' => $request->first_name,
            'employee_lastname' => $request->last_name,
            'birthday' => $request->birthday,
            'position_id' => $request->role,
            'contract_id' => $request->contract,
            'status' => 'Unlocked',
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($rawPassword),
            'pin' => $request->pin,
            'login_attempts' => 0,
        ]);

        return redirect()->route('admin.user.management.page')->with('success', 'User added successfully');
    }
}
