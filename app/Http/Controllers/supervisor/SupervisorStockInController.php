<?php

namespace App\Http\Controllers\supervisor;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupervisorStockInController extends Controller
{
    public function SupervisorStockInPage(Request $request)  // Inject Request
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 4) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an supervisor to access the dashboard.');
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

        // Build query for history_raw_materials with joins
        $query = DB::table('history_raw_materials')
            ->join('suppliers', 'history_raw_materials.supplier_id', '=', 'suppliers.id')
            ->join('products', 'history_raw_materials.product_id', '=', 'products.id')
            ->select(
                'history_raw_materials.*',
                'suppliers.supplier_name',
                'suppliers.supplier_contact_num',
                'suppliers.supplier_email_add',
                'suppliers.supplier_address',
                'products.product_name'
            )
            ->where('history_raw_materials.is_archived', 0);

        // Apply search filter on product name
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('products.product_name', 'like', '%' . $search . '%');
        }

        // Filter by supplier
        if ($request->filled('supplier')) {
            $supplierId = $request->input('supplier');
            $query->where('history_raw_materials.supplier_id', $supplierId);
        }

        // Sort by date
        if ($request->filled('sort')) {
            if ($request->input('sort') == 'newest') {
                $query->orderBy('history_raw_materials.created_at', 'desc');
            } elseif ($request->input('sort') == 'oldest') {
                $query->orderBy('history_raw_materials.created_at', 'asc');
            }
        } else {
            // Default order if no sort given
            $query->orderBy('history_raw_materials.created_at', 'desc');
        }

        $historyMaterials = $query->get();

        // Group by transact_id
        $historyGroups = $historyMaterials->groupBy('transact_id');

        // fetch notification finish products
        $lowFinishedProducts = DB::table('product_details')
            ->where('category', 'finish product')
            ->where('quantity', '<', 1000)
            ->where('is_archived', 0)
            ->get();

        $rawMaterialPOs = DB::table('purchase_orders')
            ->leftJoin('product_details', function ($join) {
                $join->on('purchase_orders.product_id', '=', 'product_details.id')
                    ->where(function ($q) {
                        $q->where('product_details.category', 'raw materials')
                            ->orWhereNull('product_details.category');
                    });
            })
            ->select('purchase_orders.po_number')
            ->where('purchase_orders.status', 'pending')
            ->distinct()
            ->orderBy('purchase_orders.po_number', 'desc')
            ->get();



        return view('supervisor.stock_in', compact(
            'role',
            'user',
            'products',
            'suppliers',
            'batchProductDetails',
            'totalAmount',
            'historyGroups',
            'lowFinishedProducts',
            'rawMaterialPOs'
        ));
    }



    public function SupervisorAddProduct(Request $request)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 4) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an supervisor to access the dashboard.');
        }

        $validated = $request->validate([
            'product_name' => 'required|string|min:3|max:255',
            'product_unit' => 'required|string|max:255',
            'product_price' => 'required|numeric|min:0.01',
        ]);

        $productId = DB::table('products')->insertGetId([
            'product_name' => $validated['product_name'],
            'stock_unit_id' => $validated['product_unit'],
            'product_price' => $validated['product_price'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('product_details')->insert([
            'product_id' => $productId,
            'product_name' => $validated['product_name'],
            'price' => $validated['product_price'],
            'quantity' => 0,
            'stock_unit_id' => $validated['product_unit'],
            'category' => 'raw materials',
            'is_archived' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('supervisor.stock.in.page')->with('success', 'Product added successfully!');
    }

    public function SupervisorAddSupplier(Request $request)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 4) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an supervisor to access the dashboard.');
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

        return redirect()->route('supervisor.stock.in.page')->with('success', 'Supplier added successfully!');
    }

    // updated

    public function SupervisorAddBatchProductDetails(Request $request)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 4) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an supervisor to access the dashboard.');
        }

        $request->validate([
            'po_number' => 'required|exists:purchase_orders,po_number',
        ], [
            'po_number.required' => 'Purchase Order number is required.',
        ]);

        $employee_id = Auth::guard('employees')->user()->id;
        $now = now();

        // Fetch supplier_id + products
        $poData = DB::table('purchase_orders')
            ->join('product_details', 'purchase_orders.product_id', '=', 'product_details.id')
            ->join('suppliers', 'purchase_orders.supplier_id', '=', 'suppliers.id') // join suppliers
            ->where('purchase_orders.po_number', $request->po_number)
            ->select(
                'purchase_orders.supplier_id',
                'purchase_orders.product_id',
                'purchase_orders.product_name',
                'purchase_orders.price',
                'purchase_orders.quantity',
                'purchase_orders.unit',
                'product_details.category'
            )
            ->get();

        if ($poData->isEmpty()) {
            return redirect()->route('supervisor.stock.in.page')->with('error', 'No products found for this PO number.');
        }

        $supplierId = $poData->first()->supplier_id; // get supplier id

        $productIds = $poData->pluck('product_id')->toArray();

        $alreadySubmitted = DB::table('batch_product_details')
            ->where('employee_id', $employee_id)
            ->whereIn('product_id', $productIds)
            ->exists();

        if ($alreadySubmitted) {
            return redirect()->route('supervisor.stock.in.page')
                ->with('error', 'You have already submitted batch details for this PO.');
        }

        $insert_data = [];
        foreach ($poData as $product) {
            $insert_data[] = [
                'employee_id'   => $employee_id,
                'product_id'    => $product->product_id,
                'product_name'  => $product->product_name,
                'price'         => $product->price,
                'quantity'      => $product->quantity ?? 0,
                'stock_unit_id' => $product->unit,
                'category'      => $product->category,
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }

        DB::table('batch_product_details')->insert($insert_data);

        DB::table('purchase_orders')
            ->where('po_number', $request->po_number)
            ->update([
                'status' => 'completed',
                'updated_at' => $now,
            ]);

        // Redirect and pass selected supplier ID back to view
        session(['selected_supplier_id' => $supplierId]);
        return redirect()->route('supervisor.stock.in.page')
            ->with('success', 'Products from PO number added successfully.');
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

        // Update 'products' table
        $updatedProduct = DB::table('products')
            ->where('id', $productId)
            ->update([
                'product_price' => $request->price,
                'updated_at' => $now
            ]);

        DB::table('batch_product_details')
            ->where('product_id', $productId)
            ->update([
                'price' => $request->price,
                'updated_at' => $now
            ]);

        DB::table('product_details')
            ->where('id', $productId)
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



    // BEFORE UPDATED
    public function SupervisorRemoveBatchProduct($id)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 4) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an supervisor to access the dashboard.');
        }

        $employeeId = Auth::guard('employees')->user()->id;

        $deleted = DB::table('batch_product_details')
            ->where('id', $id)
            ->where('employee_id', $employeeId)
            ->delete();

        if ($deleted) {
            return redirect()->route('supervisor.stock.in.page')->with('success', 'Batch product removed successfully.');
        } else {
            return redirect()->route('supervisor.stock.in.page')->with('error', 'Failed to remove batch product.');
        }
    }

    // UPDATED
    // public function SupervisorRemoveBatchProduct($id)
    // {
    //     if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
    //         return redirect()->route('login.page')->with('error', 'You must be logged in as an Supervisor.');
    //     }

    //     $employeeId = Auth::guard('employees')->user()->id;

    //     $batchProduct = DB::table('batch_product_details')
    //         ->where('id', $id)
    //         ->where('employee_id', $employeeId)
    //         ->first();

    //     if (!$batchProduct) {
    //         return redirect()->route('supervisor.stock.in.page')->with('error', 'Batch product not found or not authorized.');
    //     }
    //     $deleted = DB::table('batch_product_details')
    //         ->where('id', $id)
    //         ->delete();

    //     if ($deleted) {
    //         $matchingOrders = DB::table('purchase_orders')
    //             ->where('product_id', $batchProduct->product_id)
    //             ->where('product_name', $batchProduct->product_name)
    //             ->where('quantity', $batchProduct->quantity)
    //             ->where('price', $batchProduct->price)
    //             ->where('unit', $batchProduct->stock_unit_id)
    //             ->where('status', 'completed')
    //             ->get();

    //         foreach ($matchingOrders as $order) {
    //             DB::table('purchase_orders')
    //                 ->where('id', $order->id)
    //                 ->update([
    //                     'status' => 'pending',
    //                     'updated_at' => now(),
    //                 ]);
    //         }

    //         return redirect()->route('supervisor.stock.in.page')->with('success', 'Batch product removed and related PO status updated to pending.');
    //     } else {
    //         return redirect()->route('supervisor.stock.in.page')->with('error', 'Failed to remove batch product.');
    //     }
    // }


    // UPDATED AUG 22
    public function SupervisorRawStocksRequest(Request $request)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 4) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an supervisor to access the dashboard.');
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
                'is_archived'    => 0,
                'created_at'     => $now,
                'updated_at'     => $now,
            ];
        }

        // Insert history
        DB::table('history_raw_materials')->insert($historyData);

        // Merge with existing product_details if product_name + unit exists
        foreach ($productDetailsData as $data) {
            $existing = DB::table('product_details')
                ->where('product_name', $data['product_name'])
                ->where('stock_unit_id', $data['stock_unit_id'])
                ->where('category', 'raw materials')
                ->first();

            if ($existing) {
                // Update quantity
                DB::table('product_details')
                    ->where('id', $existing->id)
                    ->update([
                        'quantity'   => $existing->quantity + $data['quantity'],
                        'updated_at' => $now,
                    ]);
            } else {
                // Insert new product detail
                DB::table('product_details')->insert($data);
            }
        }

        // Sync batch_finish_raw_products quantity with product_details quantity
        foreach ($productDetailsData as $data) {
            $productDetail = DB::table('product_details')
                ->where('product_name', $data['product_name'])
                ->where('stock_unit_id', $data['stock_unit_id'])
                ->where('category', 'raw materials')
                ->first();

            if ($productDetail) {
                $finishUpdated = DB::table('batch_finish_raw_products')
                    ->where('product_name', $productDetail->product_name)
                    ->where('stock_unit_id', $productDetail->stock_unit_id)
                    ->update([
                        'quantity'   => $productDetail->quantity,
                        'updated_at' => now(),
                    ]);

                $fetchUpdated = DB::table('batch_fetch_raw_products')
                    ->where('product_name', $productDetail->product_name)
                    ->where('stock_unit_id', $productDetail->stock_unit_id)
                    ->update([
                        'quantity'   => $productDetail->quantity,
                        'updated_at' => now(),
                    ]);

            }
        }



        // Clear the batch items
        DB::table('batch_product_details')->where('employee_id', $employee->id)->delete();

        // Calculate total amount from the batch items
        $totalAmount = collect($historyData)->sum('amount');

        // Insert into sales_transactions
        DB::table('sales_transactions')->insert([
            'transaction_date'  => now()->toDateTimeString(),
            'process_by'        => $employee->employee_firstname . ' ' . $employee->employee_lastname,
            'transaction_type'  => 'stock in',
            'transaction_id'    => $transactId,
            'payment'           => 0,
            'return'            => 0,
            'debit'             => $totalAmount,
            'credit'            => 0,
            'loss'              => 0,
            'balances'          => $totalAmount,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);


        // Log the action
        ActivityLogger::log(
            $employee->id,
            'created',
            'raw_materials',
            "Processed raw materials with transaction ID: {$transactId}"
        );

        session()->forget('selected_supplier_id');

        return redirect()->route('supervisor.stock.in.page')->with('success', 'Raw stocks saved successfully!');
    }


    public function ArchiveRawStock($transactId)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 4) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an supervisor to access the dashboard.');
        }

        DB::table('history_raw_materials')
            ->where('transact_id', $transactId)
            ->update(['is_archived' => 1]);

        return back()->with('success', 'Transaction archived successfully.');
    }
}
