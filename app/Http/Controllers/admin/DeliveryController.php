<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\BatchFetchFinishProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeliveryController extends Controller
{
    public function AdminDeliveryPage(Request $request)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin to access the dashboard.');
        }

        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

        // Get filters from request
        $search = $request->input('search');
        $processBy = $request->input('process_by');
        $sort = $request->input('sort');

        // Start query
        $query = DB::table('delivery_orders')->where('is_archived', 0);

        // Apply processor filter if selected
        if ($processBy) {
            $query->where('process_by', $processBy);
        }

        // Apply search filter on transact_id or process_by
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('transact_id', 'like', '%' . $search . '%')
                    ->orWhere('process_by', 'like', '%' . $search . '%');
            });
        }

        // Apply sorting
        if ($sort === 'oldest') {
            $query->orderBy('transaction_date', 'asc');
        } else {
            // default newest first
            $query->orderBy('transaction_date', 'desc');
        }

        $deliveryOrders = $query->get()->groupBy('transact_id');

        $processors = DB::table('delivery_orders')->select('process_by')->distinct()->pluck('process_by');

        $allEmployees = DB::table('employees')
            ->select('id', 'employee_firstname', 'employee_lastname', 'position_id')
            ->get();

        $lowFinishedProducts = DB::table('product_details')
            ->where('category', 'finish product')
            ->where('quantity', '<', 1000)
            ->where('is_archived', 0)
            ->get();

        $products = DB::table('product_details')
            ->where('category', 'finish product')
            ->where('is_archived', 0)
            ->get();

        $fetch_finished_products = DB::table('batch_fetch_finish_products')->get();
        $cars = DB::table('cars')->get();
        $stores = DB::table('stores')->get();

        return view('admin.delivery_management', compact(
            'role',
            'user',
            'lowFinishedProducts',
            'products',
            'fetch_finished_products',
            'allEmployees',
            'cars',
            'stores',
            'deliveryOrders',
            'processors',
            'processBy',
            'search',
            'sort'
        ));
    }




    public function AdminDeliverySubmitBatch(Request $request)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin to access the dashboard.');
        }

        $request->validate([
            'products' => 'required|array|min:1',
            'products.*' => 'exists:product_details,id',
        ]);

        $user = Auth::guard('employees')->user();
        $products = DB::table('product_details')
            ->whereIn('id', $request->products)
            ->get();

        foreach ($products as $product) {
            BatchFetchFinishProducts::create([
                'employee_id' => $user->id,
                'product_id_details' => $product->id,
                'product_name' => $product->product_name,
                'unit' => $product->stock_unit_id,
                'price' => $product->price,
                'category' => $product->category,
            ]);
        }

        return redirect()->route('admin.delivery.management.page')->with('success', 'Batch products added successfully!');
    }



    public function AdminDeliveryAddStore(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'store_code' => 'required|string|max:50|unique:stores,store_code',
            'store_address' => 'required|string|max:255',
            'store_tel_no' => 'required|string|max:20',
            'store_cp_number' => 'required|string|max:11',
            'store_fax' => 'nullable|string|max:20',
            'store_tin' => 'nullable|string|max:20',
        ]);

        DB::table('stores')->insert([
            'store_name' => $request->store_name,
            'store_code' => $request->store_code,
            'store_address' => $request->store_address,
            'store_tel_no' => $request->store_tel_no,
            'store_cp_number' => $request->store_cp_number,
            'store_fax' => $request->store_fax,
            'store_tin' => $request->store_tin,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.delivery.management.page')->with('success', 'Store added successfully!');
    }

    public function AdminAddCar(Request $request)
    {
        $request->validate([
            'car' => 'required|string|max:255',
            'plate_number' => 'required|string|max:50|unique:cars,plate_number',
        ]);

        DB::table('cars')->insert([
            'car' => $request->car,
            'plate_number' => $request->plate_number,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.delivery.management.page')->with('success', 'Car added successfully!');
    }

    public function AdminDeliveryRemoveProduct($id)
    {
        $product = BatchFetchFinishProducts::find($id);

        if ($product) {
            $product->delete();
            return redirect()->route('admin.delivery.management.page')->with('success', 'Product removed successfully!');
        } else {
            return redirect()->route('admin.delivery.management.page')->with('error', 'Product not found.');
        }
    }

    public function AdminDeliveryAdd(Request $request)
    {
        $validated = $request->validate([
            'memo' => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
            'expected_date' => 'required|date',
            'approved_by' => 'required',
            'delivered_by' => 'required',
            'car' => 'required|exists:cars,id',
            'store' => 'required|exists:stores,id',
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:batch_fetch_finish_products,id',
            'quantity_ordered' => 'required|array',
            'quantity_ordered.*' => 'required|integer|min:1',
            'price' => 'required|array',
            'price.*' => 'required|numeric',
            'amount' => 'required|array',
            'amount.*' => 'required|numeric',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $user = Auth::guard('employees')->user();
        $storeCode = $request->store
            ? DB::table('stores')->where('id', $request->store)->value('store_code')
            : 'UNKNOWN';
        $exactDate = date('ymd');
        $monthFormatted = date('ym');
        $searchPrefix = "DO-{$storeCode}-{$monthFormatted}";
        $lastTransaction = DB::table('delivery_orders')
            ->where('transact_id', 'like', "{$searchPrefix}%")
            ->orderBy('transact_id', 'desc')
            ->first();
        $lastSeq = $lastTransaction
            ? (int)substr($lastTransaction->transact_id, -5)
            : 0;
        $nextSeq = str_pad($lastSeq + 1, 5, '0', STR_PAD_LEFT);
        $transactId = "DO-{$storeCode}-{$exactDate}{$nextSeq}";

        $deliveryOrders = [];

        foreach ($request->product_id as $key => $productId) {
            $product = DB::table('batch_fetch_finish_products')->find($productId);

            if ($product) {
                $productDetails = DB::table('product_details')->where('id', $product->product_id_details)->first();
                $currentQuantity = $productDetails ? $productDetails->quantity : 0;
                if ($currentQuantity >= $request->quantity_ordered[$key]) {
                    $newQuantity = $currentQuantity - $request->quantity_ordered[$key];
                    DB::table('product_details')
                        ->where('id', $product->product_id_details)
                        ->update(['quantity' => $newQuantity]);
                    $deliveryOrders[] = [
                        'transact_id' => $transactId,
                        'memo' => $request->memo,
                        'transaction_date' => $request->transaction_date,
                        'expected_delivery' => $request->expected_date,
                        'process_by' => "{$user->employee_firstname} {$user->employee_lastname}",
                        'approved_by' => $request->approved_by,
                        'delivered_by' => $request->delivered_by,
                        'car' => $request->car,
                        'store' => $request->store,
                        'product_name' => $product->product_name,
                        'pack' => 1,
                        'unit' => $product->unit,
                        'quantity_ordered' => $request->quantity_ordered[$key],
                        'quantity_received' => null,
                        'price' => $request->price[$key],
                        'amount' => $request->amount[$key],
                        'total_amount' => $request->total_amount,
                        'status' => 'pending',
                        'is_archived' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                } else {
                    return back()->with('error', 'Insufficient stock for product: ' . $product->product_name);
                }
            }
        }

        if (count($deliveryOrders) > 0) {
            DB::table('delivery_orders')->insert($deliveryOrders);
            DB::table('batch_fetch_finish_products')
                ->whereIn('id', $request->product_id)
                ->delete();
        }

        ActivityLogger::log(
            $user->id,
            'created',
            'delivery_orders',
            "Created delivery order {$transactId} with " . count($deliveryOrders) . " product(s)"
        );


        // Redirect with success message
        return redirect()->route('admin.delivery.management.page')
            ->with('success', 'Delivery added successfully!');
    }

    public function AdminViewDeliveryOrder($transact_id)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin to access the dashboard.');
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

        return view('admin.delivery.delivery_order', compact('delivery', 'first'));
    }

    public function AdminArchiveDeliveryOrder($transact_id)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin to access the dashboard.');
        }

        $updated = DB::table('delivery_orders')
            ->where('transact_id', $transact_id)
            ->update(['is_archived' => 1]);

        if ($updated) {
            ActivityLogger::log(
                Auth::guard('employees')->user()->id,
                'archived',
                'delivery_orders',
                "Archived delivery order {$transact_id}"
            );

            return redirect()->back()->with('success', 'Delivery order archived successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to archive delivery order.');
        }
    }
}
