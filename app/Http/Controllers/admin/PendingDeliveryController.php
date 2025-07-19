<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PendingDeliveryController extends Controller
{
    public function AdminPendingDeliveryPage()
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

        $deliveryOrders = DB::table('delivery_orders')
            ->leftJoin('employees', 'delivery_orders.delivered_by', '=', 'employees.id')
            ->select(
                'delivery_orders.*',
                DB::raw("CONCAT(employees.employee_firstname, ' ', employees.employee_lastname) as delivered_by_name")
            )
            ->where('delivery_orders.status', 'pending')
            ->where('delivery_orders.is_archived', 0)
            ->orderBy('delivery_orders.transaction_date', 'desc')
            ->get()
            ->groupBy('transact_id');

        return view('admin.pending_delivery', compact('role', 'user', 'lowFinishedProducts', 'deliveryOrders'));
    }

    public function AdminMarkStatusDelivery(Request $request, $transact_id)
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
            $dataToUpdate['upload_image'] = $imageName;
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

        return redirect()->back()->with('success', "Delivery marked as {$status}.");
    }
}
