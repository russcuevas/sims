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

    public function AdminUserManagementPage(Request $request)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin to access the dashboard.');
        }

        $positions = DB::table('positions')->get();
        $contracts = DB::table('contracts')->get();

        $query = DB::table('employees')
            ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
            ->leftJoin('contracts', 'employees.contract_id', '=', 'contracts.id')
            ->select(
                'employees.id',
                'employees.employee_firstname',
                'employees.employee_lastname',
                'employees.position_id',
                'employees.contract_id',
                'positions.position_name',
                'contracts.contract',
                'employees.email',
                'employees.username',
                'employees.pin',
                'employees.login_attempts',
                'employees.status'
            )
            ->where('employees.is_archived', 0);

        // filtering
        if ($request->has('role') && is_numeric($request->role)) {
            $query->where('employees.position_id', $request->role);
        }

        if ($request->has('sort') && in_array($request->sort, ['asc', 'desc'])) {
            $query->orderBy('employees.employee_lastname', $request->sort);
        }

        $employees = $query->get()->toArray();

        // fetching the user left sidebar
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

    public function AdminUpdateUser(Request $request, $id)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'role' => 'required|exists:positions,id',
            'contract' => 'required|exists:contracts,id',
            'email' => 'required|email|unique:employees,email,' . $id,
            'username' => 'required|string|unique:employees,username,' . $id . '|max:255',
            'pin' => ['required', 'digits:4'],
        ]);

        Employee::where('id', $id)->update([
            'employee_firstname' => $request->first_name,
            'employee_lastname' => $request->last_name,
            'position_id' => $request->role,
            'contract_id' => $request->contract,
            'email' => $request->email,
            'username' => $request->username,
            'pin' => $request->pin,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.user.management.page')->with('success', 'User updated successfully');
    }

    public function AdminArchiveUser($id)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'Unauthorized access.');
        }

        // Update the is_archived flag for the employee
        DB::table('employees')->where('id', $id)->update(['is_archived' => 1]);

        return redirect()->route('admin.user.management.page')->with('success', 'User archived successfully');
    }
}
