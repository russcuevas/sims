<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    public function AdminApprovalDeliveryPage(Request $request)
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

        // Get filter/sort parameters from the request
        $search = $request->input('search');
        $processBy = $request->input('process_by'); // This actually means delivered_by
        $sort = $request->input('sort');

$query = DB::table('delivery_orders')
    ->leftJoin('employees', 'delivery_orders.delivered_by', '=', 'employees.id')
    ->select(
        'delivery_orders.*',
        DB::raw("CONCAT(employees.employee_firstname, ' ', employees.employee_lastname) as delivered_by_name")
    )
    ->where('delivery_orders.status', 'pending')
    ->where('delivery_orders.is_archived', 0)
    ->where('delivery_orders.is_approved', 0); // 👈 Add this line


        // Filter: Search by product name (adjust field if needed)
        if ($search) {
            $query->where('delivery_orders.product_name', 'like', '%' . $search . '%');
            // Replace 'product_name' with actual searchable field if different
        }

        // Filter: Only by delivered_by (no processed_by logic)
        if ($processBy) {
            $query->where('delivery_orders.delivered_by', $processBy);
        }

        // Sorting: by transaction_date
        if ($sort === 'newest') {
            $query->orderBy('delivery_orders.transaction_date', 'desc');
        } elseif ($sort === 'oldest') {
            $query->orderBy('delivery_orders.transaction_date', 'asc');
        } else {
            $query->orderBy('delivery_orders.transaction_date', 'desc');
        }

        $deliveryOrders = $query->get()->groupBy('transact_id');
        $processors = DB::table('employees')
            ->select('id', DB::raw("CONCAT(employee_firstname, ' ', employee_lastname) as full_name"))
            ->get();

        return view('admin.approval', compact(
            'role',
            'user',
            'lowFinishedProducts',
            'deliveryOrders',
            'processors',
            'search',
            'processBy',
            'sort'
        ));
    }

    public function approveDelivery($transact_id)
    {
        DB::table('delivery_orders')
            ->where('transact_id', $transact_id)
            ->update(['is_approved' => 1]);

        return redirect()->back()->with('success', "Delivery with transaction ID $transact_id approved.");
    }

    public function declineDelivery($transact_id)
    {
        DB::table('delivery_orders')
            ->where('transact_id', $transact_id)
            ->delete();

        return redirect()->back()->with('success', "Delivery with transaction ID $transact_id declined.");
    }

}
