<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeliveryStatusController extends Controller
{
    public function AdminDeliveryStatusPage(Request $request)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin to access the dashboard.');
        }

        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

        $lowFinishedProducts = DB::table('product_details')
            ->where('category', 'finish product')
            ->where('quantity', '<', 1000)
            ->where('is_archived', 0)
            ->get();

        $employees = DB::table('employees')->select('id', 'employee_firstname', 'employee_lastname')->get();

        $query = DB::table('delivery_orders')
            ->leftJoin('employees', 'delivery_orders.delivered_by', '=', 'employees.id')
            ->leftJoin('history_return_items', 'delivery_orders.transact_id', '=', 'history_return_items.transact_id') // NEW
            ->select(
                'delivery_orders.*',
                DB::raw("CONCAT(employees.employee_firstname, ' ', employees.employee_lastname) as delivered_by_name"),
                'history_return_items.product'
            )
            ->whereIn('delivery_orders.status', ['completed', 'returned'])
            ->where('delivery_orders.is_archived', 0);


        if ($request->filled('search')) {
            $query->where('delivery_orders.product_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('process_by')) {
            $query->where('delivery_orders.delivered_by', $request->process_by);
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'newest') {
                $query->orderBy('delivery_orders.transaction_date', 'desc');
            } elseif ($request->sort === 'oldest') {
                $query->orderBy('delivery_orders.transaction_date', 'asc');
            }
        } else {
            $query->orderBy('delivery_orders.transaction_date', 'desc');
        }

        $deliveryOrders = $query->get()->groupBy('transact_id');

        return view('admin.delivery_status', compact('role', 'user', 'lowFinishedProducts', 'deliveryOrders', 'employees'));
    }
}
