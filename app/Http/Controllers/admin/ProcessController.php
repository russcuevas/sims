<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
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


        $products = DB::table('product_details')
            ->where('is_archived', 0)
            ->get();

        $batchProducts = DB::table('batch_fetch_raw_products')
            ->where('employee_id', Auth::guard('employees')->id())
            ->orderBy('created_at', 'desc')
            ->get();

        // fetching in left sidebar the users
        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

        return view('admin.process_management', compact('products', 'batchProducts', 'role', 'user'));
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

    public function AdminUpdateBatchRawProductQuantity(Request $request)
    {
        $employeeId = Auth::guard('employees')->id();
        $updates = $request->input('quantities', []);

        foreach ($updates as $batchId => $deductQty) {
            $deductQty = (int) $deductQty;
            if ($deductQty <= 0) continue;
            $batchProduct = DB::table('batch_fetch_raw_products')->where('id', $batchId)->where('employee_id', $employeeId)->first();

            if ($batchProduct && $batchProduct->quantity >= $deductQty) {
                DB::table('batch_fetch_raw_products')->where('id', $batchId)->decrement('quantity', $deductQty);
                DB::table('product_details')->where('id', $batchProduct->product_id_details)->decrement('quantity', $deductQty);
            }
        }

        return redirect()->back()->with('success', 'Quantities updated successfully');
    }
}
