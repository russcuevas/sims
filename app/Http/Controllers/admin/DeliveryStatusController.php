<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeliveryStatusController extends Controller
{
    public function AdminDeliveryStatusPage()
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin to access the dashboard.');
        }

        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

        // fetch notification finish products
        $lowFinishedProducts = DB::table('product_details')
            ->where('category', 'finish product')
            ->where('quantity', '<', 1000)
            ->where('is_archived', 0)
            ->get();

        // Fetch completed or returned delivery orders
        $deliveryOrders = DB::table('delivery_orders')
            ->leftJoin('employees', 'delivery_orders.delivered_by', '=', 'employees.id')
            ->select(
                'delivery_orders.*',
                DB::raw("CONCAT(employees.employee_firstname, ' ', employees.employee_lastname) as delivered_by_name")
            )
            ->whereIn('delivery_orders.status', ['completed', 'returned'])
            ->where('delivery_orders.is_archived', 0)
            ->orderBy('delivery_orders.transaction_date', 'desc')
            ->get()
            ->groupBy('transact_id');

        return view('admin.delivery_status', compact('role', 'user', 'lowFinishedProducts', 'deliveryOrders'));
    }
}
