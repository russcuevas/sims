<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function StockManagementPage(Request $request)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin.');
        }

        // fetching in left sidebar the users
        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

        $categories = DB::table('product_details')
            ->where('is_archived', 0)
            ->distinct()
            ->pluck('category');

        $query = DB::table('product_details')
            ->where('is_archived', 0);

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');

        $allowedSortBy = ['product_name', 'price', 'quantity', 'created_at'];
        $allowedSortDir = ['asc', 'desc'];

        if (!in_array($sortBy, $allowedSortBy)) {
            $sortBy = 'created_at';
        }
        if (!in_array($sortDir, $allowedSortDir)) {
            $sortDir = 'desc';
        }

        $productDetails = $query->orderBy($sortBy, $sortDir)->get();

        $lowRawMaterialsCount = DB::table('product_details')
            ->where('category', 'raw materials')
            ->where('quantity', '<=', 20)
            ->where('is_archived', 0)
            ->count();

        // fetch notification finish products
        $lowFinishedProducts = DB::table('product_details')
            ->where('category', 'finish product')
            ->where('quantity', '<', 1000)
            ->where('is_archived', 0)
            ->get();

        $purchaseOrders = DB::table('purchase_orders')
            ->select('po_number', 'process_by', 'total_amount')
            ->groupBy('po_number', 'process_by', 'total_amount')  // Avoid showing same PO multiple times
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.stock_management', compact('productDetails', 'categories', 'sortBy', 'sortDir', 'role', 'user', 'lowRawMaterialsCount', 'lowFinishedProducts', 'purchaseOrders'));
    }


    public function StockUpdateProduct(Request $request, $id)
    {
        $request->validate([
            'product_name'   => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'quantity'       => 'required|numeric|min:0',
            'stock_unit_id'  => 'required|string|max:255',
        ]);

        $productDetail = DB::table('product_details')->where('id', $id)->first();

        if (!$productDetail) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        DB::table('product_details')->where('id', $id)->update([
            'product_name'   => $request->product_name,
            'price'          => $request->price,
            'quantity'       => $request->quantity,
            'stock_unit_id'  => $request->stock_unit_id,
            'updated_at'     => now(),
        ]);

        DB::table('products')->where('id', $productDetail->product_id)->update([
            'product_name'   => $request->product_name,
            'product_price'  => $request->price,
            'stock_unit_id'  => $request->stock_unit_id,
            'updated_at'     => now(),
        ]);

        return redirect()->route('admin.stock.management.page')->with('success', 'Product updated successfully.');
    }

    public function StockArchiveProduct($id)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin.');
        }

        $product = DB::table('product_details')->where('id', $id)->first();

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        DB::table('product_details')
            ->where('id', $id)
            ->update([
                'is_archived' => 1,
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.stock.management.page')->with('success', 'Product archived successfully.');
    }

    public function StockPurchaseOrderPage()
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin.');
        }

        $user = Auth::guard('employees')->user();

        $lowStockProducts = DB::table('product_details')
            ->where('category', 'raw materials')
            ->where('quantity', '<=', 20)
            ->where('is_archived', 0)
            ->get();

        if ($lowStockProducts->isEmpty()) {
            return redirect()->back()->with('error', 'No product to request.');
        }

        $date = now()->format('ymd');
        $currentMonth = now()->format('ym');

        $lastPONumber = DB::table('purchase_orders')
            ->where('po_number', 'like', 'PO-RHEA-' . $currentMonth . '%')
            ->orderBy('po_number', 'desc')
            ->value('po_number');

        if ($lastPONumber) {
            $lastSequence = (int)substr($lastPONumber, -5);
            $nextSequence = str_pad($lastSequence + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $nextSequence = '00001';
        }

        $poNumber = 'PO-RHEA-' . $date . $nextSequence;


        $admins = DB::table('employees')
            ->where('position_id', 1)
            ->get();

        $suppliers = DB::table('suppliers')->get();

        return view('admin.purchase_order', compact('lowStockProducts', 'poNumber', 'user', 'admins', 'suppliers'));
    }


    public function StockSubmitPO(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'approved_by' => 'required|exists:employees,id',
            'total_amount' => 'required|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.product_name' => 'required|string',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.unit' => 'required|string',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.amount' => 'required|numeric|min:0',
        ]);

        $user = Auth::guard('employees')->user();

        $date = now()->format('ymd');


        $date = now()->format('ymd');
        $currentMonth = now()->format('ym');

        $lastPONumber = DB::table('purchase_orders')
            ->where('po_number', 'like', 'PO-RHEA-' . $currentMonth . '%')
            ->orderBy('po_number', 'desc')
            ->value('po_number');

        if ($lastPONumber) {
            $lastSequence = (int)substr($lastPONumber, -5);
            $newSequence = str_pad($lastSequence + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newSequence = '00001';
        }

        $poNumber = 'PO-RHEA-' . $date . $newSequence;

        foreach ($request->products as $product) {
            DB::table('purchase_orders')->insert([
                'po_number' => $poNumber,
                'supplier_id' => $request->supplier_id,
                'process_by' => $user->employee_firstname . ' ' . $user->employee_lastname,
                'approved_by' => $request->approved_by,
                'product_name' => $product['product_name'],
                'quantity' => $product['quantity'],
                'unit' => $product['unit'],
                'price' => $product['price'],
                'amount' => $product['amount'],
                'total_amount' => $request->total_amount,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('admin.stock.management.page')->with('success', 'Downloaded successfully!');
    }

    public function AdminViewPO($po_number)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin.');
        }

        $purchaseOrderItems = DB::table('purchase_orders')
            ->where('po_number', $po_number)
            ->get();

        if ($purchaseOrderItems->isEmpty()) {
            return redirect()->route('admin.view.po.history')->with('error', 'Purchase Order not found.');
        }

        $supplier_id = $purchaseOrderItems->first()->supplier_id;

        $supplier = DB::table('suppliers')->where('id', $supplier_id)->first();

        return view('admin.history.view_po_history', compact('purchaseOrderItems', 'po_number', 'supplier'));
    }
}
