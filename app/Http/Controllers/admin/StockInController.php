<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    public function StockInPage()
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin to access the dashboard.');
        }

        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

        $products = DB::table('products')->get();
        $suppliers = DB::table('suppliers')->get();

        $batchProductDetails = DB::table('batch_product_details')
            ->select('*')
            ->get()
            ->map(function ($item) {
                $item->amount = ($item->quantity > 0)
                    ? $item->quantity * $item->price
                    : $item->price;
                return $item;
            });

        $totalAmount = $batchProductDetails->sum('amount');

        $historyGroups = DB::table('history_raw_materials')
            ->join('suppliers', 'history_raw_materials.supplier_id', '=', 'suppliers.id')
            ->select(
                'history_raw_materials.*',
                'suppliers.supplier_name',
                'suppliers.supplier_contact_num',
                'suppliers.supplier_email_add',
                'suppliers.supplier_address'
            )
            ->orderBy('history_raw_materials.created_at', 'desc')
            ->get()
            ->groupBy('transact_id');


        return view('admin.stock_in', compact(
            'role',
            'user',
            'products',
            'suppliers',
            'batchProductDetails',
            'totalAmount',
            'historyGroups'
        ));
    }






    public function AdminAddProduct(Request $request)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin.');
        }

        $validated = $request->validate([
            'product_name' => 'required|string|min:3|max:255',
            'product_unit' => 'required|string|max:255',
            'product_price' => 'required|numeric|min:0.01',
        ]);

        DB::table('products')->insert([
            'product_name' => $validated['product_name'],
            'stock_unit_id' => $validated['product_unit'],
            'product_price' => $validated['product_price'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.stock.in.page')->with('success', 'Product added successfully!');
    }

    public function AdminAddSupplier(Request $request)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin.');
        }

        $validated = $request->validate([
            'supplier_name' => 'required|string|min:3|max:255',
            'supplier_contact_num' => 'required|string|min:7|max:20',
            'supplier_email_add' => 'required|email|max:255',
            'supplier_address' => 'required|string|min:5|max:500',
        ]);

        DB::table('suppliers')->insert([
            'supplier_name' => $validated['supplier_name'],
            'supplier_contact_num' => $validated['supplier_contact_num'],
            'supplier_email_add' => $validated['supplier_email_add'],
            'supplier_address' => $validated['supplier_address'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.stock.in.page')->with('success', 'Supplier added successfully!');
    }

    public function AdminAddBatchProductDetails(Request $request)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin.');
        }

        $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id',
        ], [
            'product_ids.required' => 'Product is required.',
        ]);


        $now = now();
        $employee_id = Auth::guard('employees')->user()->id;

        $products = DB::table('products')
            ->whereIn('id', $request->product_ids)
            ->get();

        $insert_data = [];

        foreach ($products as $product) {
            $insert_data[] = [
                'product_id'    => $product->id,
                'product_name'  => $product->product_name,
                'price'         => $product->product_price,
                'quantity'      => 0,
                'stock_unit_id' => $product->stock_unit_id,
                'category'      => null,
                'employee_id'   => $employee_id,
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }

        DB::table('batch_product_details')->insert($insert_data);
        return redirect()->route('admin.stock.in.page')->with('success', 'Product details added successfully.');
    }

    public function updateQuantity(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:0'
        ]);

        $employeeId = Auth::guard('employees')->id();

        $updated = DB::table('batch_product_details')
            ->where('id', $id)
            ->where('employee_id', $employeeId)
            ->update([
                'quantity' => $request->quantity,
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => $updated ? true : false
        ]);
    }

    public function UpdateProductPrice(Request $request, $productId)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        $now = now();

        $updatedProduct = DB::table('products')
            ->where('id', $productId)
            ->update([
                'product_price' => $request->price,
                'updated_at' => $now
            ]);

        $updatedBatch = DB::table('batch_product_details')
            ->where('product_id', $productId)
            ->update([
                'price' => $request->price,
                'updated_at' => $now
            ]);

        if ($updatedProduct) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to update price.']);
        }
    }



    public function AdminRemoveBatchProduct($id)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin.');
        }

        $employeeId = Auth::guard('employees')->user()->id;

        $deleted = DB::table('batch_product_details')
            ->where('id', $id)
            ->where('employee_id', $employeeId)
            ->delete();

        if ($deleted) {
            return redirect()->route('admin.stock.in.page')->with('success', 'Batch product removed successfully.');
        } else {
            return redirect()->route('admin.stock.in.page')->with('error', 'Failed to remove batch product.');
        }
    }


    public function AdminRawStocksRequest(Request $request)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin.');
        }

        $request->validate([
            'received_date' => 'required|date',
            'supplier' => 'required|exists:suppliers,id',
        ]);

        $employee = Auth::guard('employees')->user();
        $transactId = 'TRX-' . strtoupper(uniqid());

        $batchItems = DB::table('batch_product_details')
            ->where('employee_id', $employee->id)
            ->get();

        if ($batchItems->isEmpty()) {
            return back()->with('error', 'No batch products to process.');
        }

        $now = now();
        $historyData = [];
        $productDetailsData = [];

        foreach ($batchItems as $item) {
            $amount = ($item->quantity == 0) ? $item->price : $item->price * $item->quantity;

            $historyData[] = [
                'transact_id'   => $transactId,
                'supplier_id'   => $request->supplier,
                'product_id'    => $item->product_id,
                'quantity'      => $item->quantity,
                'unit'          => $item->stock_unit_id,
                'price'         => $item->price,
                'amount'        => $amount,
                'process_by'    => $employee->employee_firstname . ' ' . $employee->employee_lastname,
                'received_date' => $request->received_date,
                'created_at'    => $now,
                'updated_at'    => $now,
            ];

            $productDetailsData[] = [
                'product_id'     => $item->product_id,
                'product_name'   => $item->product_name,
                'price'          => $item->price,
                'quantity'       => $item->quantity,
                'stock_unit_id'  => $item->stock_unit_id,
                'category'       => 'raw materials',
                'created_at'     => $now,
                'updated_at'     => $now,
            ];
        }


        DB::table('history_raw_materials')->insert($historyData);
        DB::table('product_details')->insert($productDetailsData);
        DB::table('batch_product_details')->where('employee_id', $employee->id)->delete();

        return redirect()->route('admin.stock.in.page')->with('success', 'Raw stocks saved successfully!');
    }
}
