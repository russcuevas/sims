<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    public function StockInPage()
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin to access the dashboard.');
        }

        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

        // Fetch products and suppliers
        $products = DB::table('products')->get();
        $suppliers = DB::table('suppliers')->get();

        return view('admin.stock_in', compact('role', 'user', 'products', 'suppliers'));
    }



    public function AdminAddProduct(Request $request)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin.');
        }

        $validated = $request->validate([
            'product_name' => 'required|string|min:3|max:255',
            'product_unit' => 'required|string|max:255',
            'product_price' => 'required|numeric|min:0.01',
        ]);

        DB::table('products')->insert([
            'product_name' => $validated['product_name'],
            'stock_unit_id' => $validated['product_unit'],
            'product_price' => $validated['product_price'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.stock.in.page')->with('success', 'Product added successfully!');
    }

    public function AdminAddSupplier(Request $request)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin.');
        }

        $validated = $request->validate([
            'supplier_name' => 'required|string|min:3|max:255',
            'supplier_contact_num' => 'required|string|min:7|max:20',
            'supplier_email_add' => 'required|email|max:255',
            'supplier_address' => 'required|string|min:5|max:500',
        ]);

        DB::table('suppliers')->insert([
            'supplier_name' => $validated['supplier_name'],
            'supplier_contact_num' => $validated['supplier_contact_num'],
            'supplier_email_add' => $validated['supplier_email_add'],
            'supplier_address' => $validated['supplier_address'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.stock.in.page')->with('success', 'Supplier added successfully!');
    }
}
