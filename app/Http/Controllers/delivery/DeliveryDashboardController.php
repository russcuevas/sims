<?php

namespace App\Http\Controllers\delivery;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeliveryDashboardController extends Controller
{
    public function DeliveryPendingDeliveryPage(Request $request)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 3) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an delivery to access the dashboard.');
        }

        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

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
            ->where('delivery_orders.delivered_by', $user->id);

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

        return view('delivery.dashboard', compact(
            'role',
            'user',
            'deliveryOrders',
            'processors',
            'search',
            'processBy',
            'sort'
        ));
    }


    public function DeliveryMarkStatusDelivery(Request $request, $transact_id)
    {
        $request->validate([
            'upload_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'upload_notes' => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('upload_image')) {
            $image = $request->file('upload_image');
            $imageName = $image->hashName();
            $image->storeAs('public/upload_images', $imageName);
            $imagePath = $imageName;
        }

        if ($request->has('completed')) {
            $status = 'completed';
        } elseif ($request->has('returned')) {
            $status = 'returned';
        } else {
            return redirect()->back()->with('error', 'Invalid action.');
        }

        DB::table('delivery_orders')
            ->where('transact_id', $transact_id)
            ->update([
                'status' => $status,
                'upload_image' => $imagePath,
                'upload_notes' => $request->upload_notes,
                'updated_at' => now(),
            ]);

        $user = Auth::guard('employees')->user();
        ActivityLogger::log(
            $user->id,
            'updated',
            'delivery_orders',
            "Marked delivery {$transact_id} as {$status}"
        );

        return redirect()->back()->with('success', "Delivery marked as {$status}.");
    }

    public function DeliveryViewDeliveryOrder($transact_id)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 3) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an delivery to access the dashboard.');
        }

        $delivery = DB::table('delivery_orders')
            ->leftJoin('employees as approved', 'delivery_orders.approved_by', '=', 'approved.id')
            ->leftJoin('employees as delivered', 'delivery_orders.delivered_by', '=', 'delivered.id')
            ->leftJoin('stores', 'delivery_orders.store', '=', 'stores.id')
            ->leftJoin('cars', 'delivery_orders.car', '=', 'cars.id')
            ->select(
                'delivery_orders.*',
                DB::raw("CONCAT(approved.employee_firstname, ' ', approved.employee_lastname) as approved_by_name"),
                DB::raw("CONCAT(delivered.employee_firstname, ' ', delivered.employee_lastname) as delivered_by_name"),
                'stores.store_name',
                'stores.store_code',
                'stores.store_address',
                'stores.store_tel_no',
                'stores.store_cp_number',
                'stores.store_fax',
                'stores.store_tin',
                'cars.car as car_name',
                'cars.plate_number'
            )
            ->where('delivery_orders.transact_id', $transact_id)
            ->where('delivery_orders.delivered_by', Auth::guard('employees')->user()->id)
            ->get();


        if ($delivery->isEmpty()) {
            abort(404);
        }

        $first = $delivery->first();

        return view('delivery.delivery.delivery_order', compact('delivery', 'first'));
    }
}
