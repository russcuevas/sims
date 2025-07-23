<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\BatchProductMultipleUnits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ProcessController extends Controller
{
    public function ProcessManagementPage(Request $request)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin.');
        }

        $user = Auth::guard('employees')->user();

        $products = DB::table('product_details')
            ->where('is_archived', 0)
            ->get();

        $batchProducts = DB::table('batch_fetch_raw_products')
            ->where('employee_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $multipleUnitProducts = DB::table('batch_product_multiple_units')
            ->select('product_name')
            ->groupBy('product_name')
            ->get();

        $hasFinishProducts = DB::table('batch_finish_products')
            ->where('employee_id', $user->id)
            ->exists();

        $finishProducts = DB::table('batch_finish_products')
            ->where('employee_id', $user->id)
            ->get();

        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

        $historyQuery = DB::table('history_finish_products')
            ->where('is_archived', 0);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $historyQuery->where('product_name', 'like', "%{$search}%");
        }

        if ($request->filled('process_by')) {
            $historyQuery->where('process_by', $request->input('process_by'));
        }

        if ($request->filled('date_from')) {
            $historyQuery->whereDate('process_date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $historyQuery->whereDate('process_date', '<=', $request->input('date_to'));
        }

        if ($request->filled('sort')) {
            if ($request->input('sort') == 'newest') {
                $historyQuery->orderBy('process_date', 'desc');
            } elseif ($request->input('sort') == 'oldest') {
                $historyQuery->orderBy('process_date', 'asc');
            }
        } else {
            $historyQuery->orderBy('process_date', 'desc');
        }

        $historyRecords = $historyQuery->get()->groupBy('transact_id');

        // Get distinct processors for the filter dropdown
        $processors = DB::table('history_finish_products')
            ->where('is_archived', 0)
            ->distinct()
            ->pluck('process_by');


        // fetch notification finish products
        $lowFinishedProducts = DB::table('product_details')
            ->where('category', 'finish product')
            ->where('quantity', '<', 1000)
            ->where('is_archived', 0)
            ->get();

        return view('admin.process_management', compact(
            'products',
            'batchProducts',
            'role',
            'user',
            'multipleUnitProducts',
            'hasFinishProducts',
            'finishProducts',
            'historyRecords',
            'processors',
            'lowFinishedProducts'   // <-- added here
        ));
    }





    public function AdminRemoveBatchRawProduct($id)
    {
        DB::table('batch_fetch_raw_products')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Product removed from batch.');
    }


    public function AdminAddBatchFetchRawProducts(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*' => 'exists:product_details,id',
        ]);

        $selectedIds = $request->input('products');

        $employeeId = Auth::guard('employees')->id();

        $products = DB::table('product_details')
            ->whereIn('id', $selectedIds)
            ->get();

        foreach ($products as $product) {
            DB::table('batch_fetch_raw_products')->insert([
                'employee_id'        => $employeeId,
                'product_id_details' => $product->id,
                'product_name'       => $product->product_name,
                'price'              => $product->price,
                'quantity'           => $product->quantity,
                'stock_unit_id'      => $product->stock_unit_id,
                'category'           => $product->category,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Selected products added to batch successfully.');
    }

    public function AdminAddBatchMultipleProduct(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price_80g'    => 'nullable|numeric|min:0',
            'price_130g'   => 'nullable|numeric|min:0',
            'price_230g'   => 'nullable|numeric|min:0',
        ]);

        $productName = $request->input('product_name');

        $units = [
            '80g' => $request->input('price_80g'),
            '130g' => $request->input('price_130g'),
            '230g' => $request->input('price_230g'),
        ];

        foreach ($units as $unit => $price) {
            if ($price !== null) {
                BatchProductMultipleUnits::create([
                    'product_name'  => $productName,
                    'stock_unit_id' => $unit,
                    'product_price' => $price,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Product added successfully.');
    }

    public function AddBatchFinishProduct(Request $request)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin.');
        }

        $request->validate([
            'batch_product_name' => 'required|string',
        ]);

        $employeeId = Auth::guard('employees')->id();
        $products = BatchProductMultipleUnits::where('product_name', $request->batch_product_name)->get();

        foreach ($products as $product) {
            DB::table('batch_finish_products')->insert([
                'employee_id'    => $employeeId,
                'quantity'       => 100,
                'product_name'   => $product->product_name,
                'stock_unit_id'  => $product->stock_unit_id,
                'product_price'  => $product->product_price,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        return response()->json(['message' => 'Inserted successfully.']);
    }

    public function AdminFinishProductSubmit(Request $request)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin.');
        }

        $request->validate([
            'process_date' => 'required|date',
            'quantities'   => 'required|array',
            'prices'       => 'required|array',
        ]);

        $employee = Auth::guard('employees')->user();
        $transactId = 'FP-' . strtoupper(Str::random(8));
        $processDate = $request->process_date;
        $now = now();

        $finishItems = DB::table('batch_finish_products')
            ->where('employee_id', $employee->id)
            ->get();

        if ($finishItems->isEmpty()) {
            return back()->with('error', 'No finish products to process.');
        }

        $historyData = [];
        $productDetailsData = [];

        foreach ($finishItems as $item) {
            $id = $item->id;

            $updatedQuantity = $request->quantities[$id] ?? $item->quantity;
            $updatedPrice    = $request->prices[$id] ?? $item->product_price;

            $amount = $updatedQuantity * $updatedPrice;

            // Insert into history
            $historyData[] = [
                'transact_id'   => $transactId,
                'product_name'  => $item->product_name,
                'quantity'      => $updatedQuantity,
                'unit'          => $item->stock_unit_id,
                'price'         => $updatedPrice,
                'amount'        => $amount,
                'process_by'    => $employee->employee_firstname . ' ' . $employee->employee_lastname,
                'process_date'  => $processDate,
                'created_at'    => $now,
                'updated_at'    => $now,
            ];

            $productDetailsData[] = [
                'product_id'     => null,
                'product_name'   => $item->product_name,
                'price'          => $updatedPrice,
                'quantity'       => $updatedQuantity,
                'stock_unit_id'  => $item->stock_unit_id,
                'category'       => 'finish product',
                'is_archived'    => 0,
                'created_at'     => $now,
                'updated_at'     => $now,
            ];
        }

        // Handle raw material deduction
        foreach ($request->input('raw_quantities', []) as $batchId => $deductQty) {
            $rawProduct = DB::table('batch_fetch_raw_products')->where('id', $batchId)->first();

            if ($rawProduct) {
                $newBatchQty = $rawProduct->quantity - $deductQty;

                if ($newBatchQty <= 0) {
                    DB::table('batch_fetch_raw_products')->where('id', $batchId)->delete();
                } else {
                    DB::table('batch_fetch_raw_products')->where('id', $batchId)->update([
                        'quantity'    => $newBatchQty,
                        'updated_at'  => $now,
                    ]);
                }

                if (!is_null($rawProduct->product_id_details)) {
                    $product = DB::table('product_details')->where('id', $rawProduct->product_id_details)->first();

                    if ($product) {
                        $newProductQty = $product->quantity - $deductQty;

                        DB::table('product_details')->where('id', $rawProduct->product_id_details)->update([
                            'quantity'   => max(0, $newProductQty),
                            'updated_at' => $now,
                        ]);
                    }
                }
            }
        }

        // Save to history
        DB::table('history_finish_products')->insert($historyData);

        // Insert or update into product_details (category = finish product)
        foreach ($productDetailsData as $data) {
            $existing = DB::table('product_details')
                ->where('product_name', $data['product_name'])
                ->where('stock_unit_id', $data['stock_unit_id'])
                ->where('category', 'finish product')
                ->first();

            if ($existing) {
                // Add quantity to existing
                DB::table('product_details')
                    ->where('id', $existing->id)
                    ->update([
                        'quantity'   => $existing->quantity + $data['quantity'],
                        'updated_at' => $now,
                    ]);
            } else {
                // Insert as new finish product
                DB::table('product_details')->insert($data);
            }
        }

        // Clean up
        DB::table('batch_finish_products')->where('employee_id', $employee->id)->delete();
        DB::table('batch_fetch_raw_products')->where('employee_id', $employee->id)->delete();

        ActivityLogger::log(
            $employee->id,
            'created',
            'finish_products',
            "Processed finished product submission with transaction ID: {$transactId}"
        );

        return redirect()->route('admin.process.management.page')->with('success', 'Finished products submitted successfully');
    }


    public function AdminRemoveFinishProduct($id)
    {
        DB::table('batch_finish_products')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Finish product removed successfully.');
    }


    public function AdminArchiveHistoryFinishProduct(Request $request, $transactId)
    {
        DB::table('history_finish_products')
            ->where('transact_id', $transactId)
            ->update(['is_archived' => 1]);

        return redirect()->back()->with('success', 'History archived successfully.');
    }
}
