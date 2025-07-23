<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileManagement extends Controller
{
    public function AdminProfileManagementPage()
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin to access the dashboard.');
        }

        // Fetch logged-in user and role
        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

        // fetch notification finish products
        $lowFinishedProducts = DB::table('product_details')
            ->where('category', 'finish product')
            ->where('quantity', '<', 1000)
            ->where('is_archived', 0)
            ->get();

        return view('admin.edit_profile', compact('role', 'user', 'lowFinishedProducts'));
    }

    public function AdminUpdateProfile(Request $request)
    {
        $user = Auth::guard('employees')->user();

        $request->validate([
            'email' => 'required|email|unique:employees,email,' . $user->id,
            'username' => 'required|string|unique:employees,username,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        DB::table('employees')->where('id', $user->id)->update([
            'email' => $request->email,
            'username' => $request->username,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
