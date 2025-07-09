<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BatchFinishProduct;
use App\Models\BatchProductMultipleUnits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ProcessController extends Controller
{
    public function ProcessManagementPage()
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

        return view('admin.process_management', compact(
            'products',
            'batchProducts',
            'role',
            'user',
            'multipleUnitProducts',
            'hasFinishProducts',
            'finishProducts'
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
}
