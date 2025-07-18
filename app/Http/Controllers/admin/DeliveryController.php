<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BatchFetchFinishProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeliveryController extends Controller
{
    public function AdminDeliveryPage()
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin to access the dashboard.');
        }

        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

        $allEmployees = DB::table('employees')
            ->select('employees.id', 'employees.employee_firstname', 'employees.employee_lastname', 'employees.position_id')
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

        $fetch_finished_products = DB::table('batch_fetch_finish_products')
            ->get();

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
            'transaction_date' => 'nullable|date',
            'expected_date' => 'nullable|date',
            'approved_by' => 'nullable',
            'delivered_by' => 'nullable',
            'car' => 'nullable|exists:cars,id',
            'store' => 'nullable|exists:stores,id',
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
        $transactId = uniqid('transact_');
        $deliveryOrders = [];

        // Loop through each product ordered
        foreach ($request->product_id as $key => $productId) {
            // Retrieve the product details from `batch_fetch_finish_products`
            $product = DB::table('batch_fetch_finish_products')->find($productId);

            if ($product) {
                // Retrieve the corresponding product quantity from `product_details`
                $productDetails = DB::table('product_details')->where('id', $product->product_id_details)->first();
                $currentQuantity = $productDetails ? $productDetails->quantity : 0;

                // Ensure there's enough stock to fulfill the order
                if ($currentQuantity >= $request->quantity_ordered[$key]) {
                    // Calculate the new quantity after subtracting the ordered quantity
                    $newQuantity = $currentQuantity - $request->quantity_ordered[$key];

                    // Update the product quantity in the `product_details` table
                    DB::table('product_details')
                        ->where('id', $product->product_id_details)
                        ->update(['quantity' => $newQuantity]);

                    // Prepare the delivery order data
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
                        'pack' => 1,  // Fixed value, can be dynamic if needed
                        'unit' => $product->unit,
                        'quantity_ordered' => $request->quantity_ordered[$key],
                        'quantity_received' => null,  // Initially null
                        'price' => $request->price[$key],
                        'amount' => $request->amount[$key],
                        'total_amount' => $request->total_amount,
                        'is_archived' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                } else {
                    return back()->with('error', 'Insufficient stock for product: ' . $product->product_name);
                }
            }
        }

        // Insert all records in bulk if there are any orders to insert
        if (count($deliveryOrders) > 0) {
            DB::table('delivery_orders')->insert($deliveryOrders);
        }

        // Redirect with success message
        return redirect()->route('admin.delivery.management.page')
            ->with('success', 'Delivery Order Added Successfully!');
    }
}
