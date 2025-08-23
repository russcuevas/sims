<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManagerPaymentItemController extends Controller
{
    public function ManagerPaymentItemPage()
    {

        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 2) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an manager to access the dashboard.');
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

        $returnedDeliveryOrders = DB::table('delivery_orders')
            ->where('is_archived', 0)
            ->where('status', 'completed')
            ->whereNotNull('quantity_returned')
            ->where(function ($query) {
                $query->whereNull('payment_amount')
                    ->orWhere('payment_amount', 0);
            })
            ->orderBy('transaction_date', 'desc')
            ->get()
            ->groupBy('transact_id');


        $deliveryOrders = collect(); // Optional safeguard

        $paidDeliveryOrders = DB::table('delivery_orders')
            ->where('is_archived', 0)
            ->where('status', 'completed')
            ->whereNotNull('payment_amount')
            ->where('payment_amount', '>', 0)
            ->orderBy('transaction_date', 'desc')
            ->get()
            ->groupBy('transact_id');


        return view('manager.delivery_payment', compact(
            'role',
            'user',
            'lowFinishedProducts',
            'returnedDeliveryOrders',
            'deliveryOrders',
            'paidDeliveryOrders'
        ));
    }

    public function fetchDeliveryOrderDetails(Request $request)
    {
        $request->validate([
            'transact_id' => 'required|string'
        ]);

        $transactId = $request->input('transact_id');

        // Fetch delivery order details with returned quantities
        $deliveryOrders = DB::table('delivery_orders')
            ->where('transact_id', $transactId)
            ->whereNotNull('quantity_returned')
            ->where('is_archived', 0)
            ->get();

        // Fetch user and role (if needed)
        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

        // Fetch additional data again if needed
        $lowFinishedProducts = DB::table('product_details')
            ->where('category', 'finish product')
            ->where('quantity', '<', 1000)
            ->where('is_archived', 0)
            ->get();

        // Fetch all returned delivery orders for the dropdown
        $returnedDeliveryOrders = DB::table('delivery_orders')
            ->where('is_archived', 0)
            ->where('status', 'completed')
            ->whereNotNull('quantity_returned')
            ->where(function ($query) {
                $query->whereNull('payment_amount')
                    ->orWhere('payment_amount', 0);
            })
            ->orderBy('transaction_date', 'desc')
            ->get()
            ->groupBy('transact_id');

        $paidDeliveryOrders = DB::table('delivery_orders')
            ->where('is_archived', 0)
            ->where('status', 'completed')
            ->whereNotNull('payment_amount')
            ->where('payment_amount', '>', 0)
            ->orderBy('transaction_date', 'desc')
            ->get()
            ->groupBy('transact_id');

        return view('manager.delivery_payment', compact(
            'role',
            'user',
            'lowFinishedProducts',
            'returnedDeliveryOrders',
            'deliveryOrders',
            'paidDeliveryOrders'
        ));
    }
    public function updateTransactPayment(Request $request)
    {
        $request->validate([
            'transact_id' => 'required|string',
            'payment_amount' => 'required|numeric|min:0'
        ]);

        // Update delivery_orders
        DB::table('delivery_orders')
            ->where('transact_id', $request->transact_id)
            ->update([
                'payment_amount' => $request->payment_amount,
                'updated_at' => now()
            ]);

        // Fetch delivery order details
        $delivery = DB::table('delivery_orders')
            ->where('transact_id', $request->transact_id)
            ->first();

        if ($delivery) {
            // Insert into sales_transactions
            DB::table('sales_transactions')->insert([
                'transaction_date' => now(),
                'process_by'       => $delivery->process_by,   // from delivery_orders
                'transaction_type' => 'payment',
                'transaction_id'   => $delivery->transact_id,  // from delivery_orders
                'payment'          => $request->payment_amount,
                'return'           => 0,
                'debit'            => 0,
                'credit'           => $request->payment_amount,
                'loss'             => 0,
                'balances'         => $request->payment_amount,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }

        return redirect()->route('manager.payment.item.page')
            ->with('success', 'Payment amount updated and recorded in sales transactions.');
    }

    public function ManagerPrintPaymentOrder($transact_id)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 2) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an manager to access the dashboard.');
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
            ->get();

        if ($delivery->isEmpty()) {
            abort(404);
        }

        $first = $delivery->first();

        return view('manager.payment.delivery_order', compact('delivery', 'first'));
    }
}
