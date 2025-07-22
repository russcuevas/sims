<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnItemController extends Controller
{
    public function AdminReturnItemPage(Request $request)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin to access the dashboard.');
        }

        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

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

        $stores = DB::table('stores')->get();

        $batchProducts = DB::table('batch_return_item_products')
            ->orderBy('created_at', 'desc')
            ->get();

        $query = DB::table('history_return_items')
            ->leftJoin('employees', 'history_return_items.picked_up_by', '=', 'employees.id')
            ->select(
                'history_return_items.*',
                'employees.employee_firstname',
                'employees.employee_lastname'
            );


        if ($request->has('search') && $request->search != '') {
            $query->where('history_return_items.product', 'like', '%' . $request->search . '%');
        }


        if ($request->has('process_by') && $request->process_by != '') {
            $query->where('history_return_items.process_by', $request->process_by);
        }

        if ($request->has('sort')) {
            if ($request->sort == 'newest') {
                $query->orderBy('history_return_items.transaction_date', 'desc');
            } elseif ($request->sort == 'oldest') {
                $query->orderBy('history_return_items.transaction_date', 'asc');
            }
        } else {
            $query->orderBy('history_return_items.transaction_date', 'desc');
        }

        $historyReturns = $query->get()->groupBy('transact_id');

        return view('admin.return_item', compact(
            'role',
            'user',
            'allEmployees',
            'products',
            'stores',
            'batchProducts',
            'lowFinishedProducts',
            'historyReturns'
        ));
    }


    public function AdminBatchReturnProductSubmit(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
        ]);

        $selectedIds = $request->input('products');

        $employeeId = Auth::guard('employees')->id();

        $products = ProductDetail::whereIn('id', $selectedIds)->get();

        foreach ($products as $product) {
            DB::table('batch_return_item_products')->insert([
                'employee_id'        => $employeeId,
                'product_name'       => $product->product_name,
                'price'              => $product->price,
                'stock_unit_id'      => $product->stock_unit_id,
                'category'           => 'finish product',
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Selected products added to batch successfully.');
    }

    public function AdminAddReturnItem(Request $request)
    {
        $request->validate([
            'transaction_date' => 'required|date',
            'picked_up_by' => 'required|exists:employees,id',
            'store' => 'required|exists:stores,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'amount' => 'required|array',
            'amount.*' => 'required|numeric|min:0',
        ]);

        $user = Auth::guard('employees')->user();

        // Generate unique transact_id
        $storeCode = DB::table('stores')->where('id', $request->store)->value('store_code') ?? 'XXX';
        $now = now();
        $datePart = $now->format('ymd');

        $last = DB::table('history_return_items')
            ->where('transact_id', 'like', "RTN-$storeCode-$datePart%")
            ->orderBy('transact_id', 'desc')
            ->first();

        $lastSeq = $last ? (int)substr($last->transact_id, -5) : 0;
        $nextSeq = str_pad($lastSeq + 1, 5, '0', STR_PAD_LEFT);
        $transactId = "RTN-$storeCode-$datePart$nextSeq";

        $batchItems = DB::table('batch_return_item_products')->get();

        foreach ($batchItems as $index => $item) {
            DB::table('history_return_items')->insert([
                'transact_id' => $transactId,
                'transaction_date' => $request->transaction_date,
                'process_by' => $user->employee_firstname . ' ' . $user->employee_lastname,
                'picked_up_by' => $request->picked_up_by,
                'store_id' => $request->store,
                'product' => $item->product_name,
                'quantity' => $request->quantity[$index],
                'unit' => $item->stock_unit_id,
                'price' => $item->price,
                'amount' => $request->amount[$index],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        ActivityLogger::log(
            $user->id,
            'added',
            'return_items',
            "Submitted return items transaction {$transactId}"
        );

        // Clear batch
        DB::table('batch_return_item_products')->truncate();
        return redirect()->route('admin.return.item.page')->with('success', 'Return item submitted successfully.');
    }

    public function AdminDeleteBatchReturnProduct($id)
    {
        DB::table('batch_return_item_products')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Item removed successfully.');
    }
}
