<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function AdminDashboardPage()
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin to access the dashboard.');
        }

        // Fetch logged-in user and role
        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

        // Fetch notification finish products
        $lowFinishedProducts = DB::table('product_details')
            ->where('category', 'finish product')
            ->where('quantity', '<', 1000)
            ->where('is_archived', 0)
            ->get();

        $pendingOrders = DB::table('delivery_orders')
            ->where('status', 'pending')
            ->where('is_archived', 0)
            ->distinct('transact_id')
            ->count('transact_id');

        $completedOrders = DB::table('delivery_orders')
            ->where('status', 'completed')
            ->where('is_archived', 0)
            ->distinct('transact_id')
            ->count('transact_id');

        $returnOrders = DB::table('delivery_orders')
            ->where('status', 'return')
            ->where('is_archived', 0)
            ->distinct('transact_id')
            ->count('transact_id');


        return view('admin.dashboard', compact(
            'role',
            'user',
            'lowFinishedProducts',
            'pendingOrders',
            'completedOrders',
            'returnOrders'
        ));
    }

    public function AdminGetMonthlySales(Request $request)
    {
        $year = $request->input('year', date('Y'));

        $data = DB::table('delivery_orders')
            ->selectRaw('MONTH(created_at) as month, COALESCE(SUM(amount), 0) as total')
            ->whereYear('created_at', $year)
            ->where('status', 'completed')
            ->where('is_archived', '!=', 1)
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->get()
            ->keyBy('month')
            ->all();

        $numbers = [];
        for ($m = 1; $m <= 12; $m++) {
            $numbers[] = isset($data[$m]) ? (float) $data[$m]->total : 0;
        }

        return response()->json([
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'data' => $numbers,
        ]);
    }
    public function AdminGetAvailableProductsByType(Request $request)
    {
        $type = $request->input('type', 'raw materials');

        $products = DB::table('product_details')
            ->select('product_name', 'stock_unit_id', 'quantity')
            ->where('category', $type)
            ->where('is_archived', '!=', 1)
            ->get();

        $labels = $products->map(function ($item) {
            return "{$item->product_name} ({$item->stock_unit_id})";
        });

        $data = $products->pluck('quantity');

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
