<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use Illuminate\Support\Facades\Mail;

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

        // fetch notification finish products
        $lowFinishedProducts = DB::table('product_details')
            ->where('category', 'finish product')
            ->where('quantity', '<', 1000)
            ->where('is_archived', 0)
            ->get();

        return view('admin.user_management', compact('positions', 'contracts', 'employees', 'role', 'user', 'lowFinishedProducts'));
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
            'pin' => ['required', 'digits:4'],
        ]);

        $firstName = strtolower(explode(' ', trim($request->first_name))[0]);
        $lastName = strtolower(trim($request->last_name));
        $firstLetterLastName = substr($lastName, 0, 1);
        $username = $firstName . $firstLetterLastName;

        $birthYear = date('Y', strtotime($request->birthday));
        $rawPassword = $lastName . $birthYear;

        $originalUsername = $username;
        $counter = 1;
        while (\App\Models\Employee::where('username', $username)->exists()) {
            $username = $originalUsername . $counter;
            $counter++;
        }

        Employee::create([
            'employee_firstname' => $request->first_name,
            'employee_lastname' => $request->last_name,
            'birthday' => $request->birthday,
            'position_id' => $request->role,
            'contract_id' => $request->contract,
            'status' => 'Unlocked',
            'email' => $request->email,
            'username' => $username,
            'password' => Hash::make($rawPassword),
            'pin' => $request->pin,
            'login_attempts' => 0,
        ]);

        // Send simple email
        Mail::raw("Welcome to the system!\n\nYour default login credentials are:\nUsername: {$username}\nPassword: {$rawPassword}\n\nPlease change your password after logging in.", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Your Default Login Credentials');
        });

        return redirect()->route('admin.user.management.page')
            ->with('success', "User added successfully please check the email to logged in your account!");
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
            'status' => 'required|string|in:Locked,Unlocked', // Add valid statuses
        ]);

        $updateData = [
            'employee_firstname' => $request->first_name,
            'employee_lastname' => $request->last_name,
            'position_id' => $request->role,
            'contract_id' => $request->contract,
            'email' => $request->email,
            'username' => $request->username,
            'pin' => $request->pin,
            'status' => $request->status,
        ];

        if ($request->status === 'Unlocked') {
            $updateData['login_attempts'] = 0;
            $updateData['status'] = 'Unlocked';
        }

        Employee::where('id', $id)->update($updateData);

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
