<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function StockManagementPage()
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin.');
        }

        $productDetails = DB::table('product_details')
            ->select('*')
            ->get();

        return view('admin.stock_management', compact('productDetails'));
    }
}
