<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\BatchProductMultipleUnits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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

        // UPDATED AUG 22
        $multipleUnitProducts = DB::table('batch_product_multiple_units as bpmu')
            ->select('bpmu.product_name')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('batch_finish_raw_products as bfrp')
                    ->whereColumn('bfrp.product_name', 'bpmu.product_name')
                    ->whereColumn('bfrp.identity_no', 'bpmu.identity_no');
            })
            ->groupBy('bpmu.product_name')
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

        // changes
        $rawHistoryRecords = DB::table('history_finish_product_raws')
            ->get()
            ->groupBy('transact_id');

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
            'lowFinishedProducts',
            'rawHistoryRecords'
        ));
    }





    public function AdminRemoveBatchRawProduct($id)
    {
        DB::table('batch_fetch_raw_products')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Product removed from batch.');
    }

    // UPDATED AUG 22
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

    // UPDATED AUG 22
    public function AdminAddBatchMultipleProduct(Request $request)
    {
        // Validate inputs
        $request->validate([
            'product_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('batch_product_multiple_units', 'product_name'),
            ],
            'quantity_1' => 'required|integer|min:1',
            'unit_1' => 'required|string|max:50',
            'price_1' => 'required|numeric|min:0',

            'quantity_2' => 'required|integer|min:1',
            'unit_2' => 'required|string|max:50',
            'price_2' => 'required|numeric|min:0',

            'quantity_3' => 'required|integer|min:1',
            'unit_3' => 'required|string|max:50',
            'price_3' => 'required|numeric|min:0',

            'ingredients' => 'nullable|array',
            'ingredients.*.id' => 'required_with:ingredients.*.quantity|exists:product_details,id',
            'ingredients.*.unit' => 'required_with:ingredients.*.id|string|max:50',
        ], [
            'product_name.unique' => 'Product name already added.',
        ]);

        $productName = $request->input('product_name');
        $employeeId = Auth::guard('employees')->id();

        // ✅ Generate a shared identity number for this batch
        $identityNo = 'BATCH-' . strtoupper(uniqid());

        // Save product units
        $unitsData = [
            [
                'unit' => $request->input('unit_1'),
                'quantity' => $request->input('quantity_1'),
                'price' => $request->input('price_1'),
            ],
            [
                'unit' => $request->input('unit_2'),
                'quantity' => $request->input('quantity_2'),
                'price' => $request->input('price_2'),
            ],
            [
                'unit' => $request->input('unit_3'),
                'quantity' => $request->input('quantity_3'),
                'price' => $request->input('price_3'),
            ],
        ];

        foreach ($unitsData as $data) {
            BatchProductMultipleUnits::create([
                'identity_no'    => $identityNo,
                'product_name'   => $productName,
                'stock_unit_id'  => $data['unit'],
                'quantity'       => $data['quantity'],
                'product_price'  => $data['price'],
            ]);
        }

        // Save ingredients if provided
        $ingredients = $request->input('ingredients', []);

        foreach ($ingredients as $ingredient) {
            $product = DB::table('product_details')->where('id', $ingredient['id'])->first();

            if ($product) {
                DB::table('batch_finish_raw_products')->insert([
                    'employee_id'         => $employeeId,
                    'product_id_details'  => $product->id,
                    'identity_no'         => $identityNo, // ✅ Same identity_no
                    'product_name'        => $product->product_name,
                    'price'               => $product->price,
                    'quantity'            => $product->quantity, // from product_details
                    'ingredient_quantity' => null, // from input
                    'stock_unit_id'       => $product->stock_unit_id,
                    'category'            => $product->category,
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Product and ingredients added successfully.');
    }



    // UPDATED AUG 22 2025
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

        if ($products->isEmpty()) {
            return response()->json(['message' => 'No products found.'], 404);
        }

        foreach ($products as $product) {
            DB::table('batch_finish_products')->insert([
                'employee_id'    => $employeeId,
                'quantity'       => $product->quantity,
                'product_name'   => $product->product_name,
                'stock_unit_id'  => $product->stock_unit_id,
                'product_price'  => $product->product_price,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        $identityNo = $products->first()->identity_no;
        $rawsToInsert = DB::table('batch_finish_raw_products')
            ->where('identity_no', $identityNo)
            ->get();

        foreach ($rawsToInsert as $raw) {
            DB::table('batch_fetch_raw_products')->insert([
                'employee_id'         => $employeeId,
                'product_id_details'  => $raw->product_id_details,
                'identity_no'         => $raw->identity_no,
                'product_name'        => $raw->product_name,
                'price'               => $raw->price,
                'ingredient_quantity' => $raw->ingredient_quantity,
                'quantity'            => $raw->quantity,
                'stock_unit_id'       => $raw->stock_unit_id,
                'category'            => $raw->category,
                'is_selected'         => 1,
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);
        }

        return response()->json(['message' => 'Products and raw materials processed successfully.']);
    }


    // UPDATED AUG 22
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

        // changes

        $rawHistoryData = [];

        // Handle raw material deduction
        foreach ($request->input('raw_quantities', []) as $batchId => $deductQty) {
            $deductQty = (int) $deductQty;
            if ($deductQty <= 0) continue;

            // Get batch_fetch_raw_products entry
            $fetchRaw = DB::table('batch_fetch_raw_products')->where('id', $batchId)->first();

            if ($fetchRaw) {
                // Deduct from batch_fetch_raw_products
                $newFetchQty = $fetchRaw->quantity - $deductQty;

                if ($newFetchQty <= 0) {
                    DB::table('batch_fetch_raw_products')->where('id', $batchId)->delete();
                } else {
                    DB::table('batch_fetch_raw_products')->where('id', $batchId)->update([
                        'quantity'   => $newFetchQty,
                        'updated_at' => $now,
                    ]);
                }

                // Deduct from batch_finish_raw_products WHERE product_id_details = fetchRaw's product_id_details
                if ($fetchRaw->product_id_details) {
                    $finishRaw = DB::table('batch_finish_raw_products')
                        ->where('product_id_details', $fetchRaw->product_id_details)
                        ->first();

                    if ($finishRaw) {
                        $newFinishQty = $finishRaw->quantity - $deductQty;

                        if ($newFinishQty <= 0) {
                            DB::table('batch_finish_raw_products')->where('id', $finishRaw->id)->update([
                                'quantity'   => 0,
                                'updated_at' => $now,
                            ]);
                        } else {
                            DB::table('batch_finish_raw_products')->where('id', $finishRaw->id)->update([
                                'quantity'   => $newFinishQty,
                                'updated_at' => $now,
                            ]);
                        }
                    }

                    // Deduct from product_details
                    $product = DB::table('product_details')->where('id', $fetchRaw->product_id_details)->first();

                    if ($product) {
                        $newProductQty = $product->quantity - $deductQty;
                        DB::table('product_details')->where('id', $product->id)->update([
                            'quantity'   => max(0, $newProductQty),
                            'updated_at' => $now,
                        ]);
                    }
                }

                $currentQty = $request->input('raw_current_quantities.' . $batchId, $fetchRaw->quantity);

                $rawHistoryData[] = [
                    'transact_id'      => $transactId,
                    'product_name'     => $fetchRaw->product_name,
                    'quantity'         => $deductQty,
                    'current_quantity' => $currentQty,
                    'unit'             => $fetchRaw->stock_unit_id,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ];
            }
        }

        // Save to history
        DB::table('history_finish_products')->insert($historyData);
        DB::table('history_finish_product_raws')->insert($rawHistoryData);

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
