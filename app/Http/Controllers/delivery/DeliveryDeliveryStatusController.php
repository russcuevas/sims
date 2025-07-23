<?php

namespace App\Http\Controllers\delivery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeliveryDeliveryStatusController extends Controller
{
    public function DeliveryDeliveryStatusPage(Request $request)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 3) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as a delivery to access the dashboard.');
        }

        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

        $query = DB::table('delivery_orders')
            ->leftJoin('employees', 'delivery_orders.delivered_by', '=', 'employees.id')
            ->leftJoin('history_return_items', 'delivery_orders.transact_id', '=', 'history_return_items.transact_id')
            ->select(
                'delivery_orders.*',
                DB::raw("CONCAT(employees.employee_firstname, ' ', employees.employee_lastname) as delivered_by_name"),
                'history_return_items.product'
            )
            ->whereIn('delivery_orders.status', ['completed', 'returned'])
            ->where('delivery_orders.is_archived', 0)
            ->where('delivery_orders.delivered_by', $user->id);

        // Optional search by product name
        if ($request->filled('search')) {
            $query->where('delivery_orders.product_name', 'like', '%' . $request->search . '%');
        }

        // (Optional) sort logic
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

        return view('delivery.delivery_status', compact('role', 'user', 'deliveryOrders'));
    }
}
