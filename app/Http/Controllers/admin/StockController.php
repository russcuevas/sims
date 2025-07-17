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


        return view('admin.stock_management', compact('productDetails', 'categories', 'sortBy', 'sortDir', 'role', 'user', 'lowRawMaterialsCount'));
    }


    public function StockUpdateProductQuantity(Request $request, $id)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'quantity' => 'required|numeric|min:0',
        ]);

        // Update product_details
        $updatedProduct = DB::table('product_details')
            ->where('id', $id)
            ->update([
                'quantity' => $validated['quantity'],
                'updated_at' => now(),
            ]);

        // Update batch_fetch_raw_products where product_id_details matches
        $updatedBatch = DB::table('batch_fetch_raw_products')
            ->where('product_id_details', $id)
            ->update([
                'quantity' => $validated['quantity'],
                'updated_at' => now(),
            ]);

        if ($updatedProduct || $updatedBatch) {
            return response()->json(['success' => true, 'message' => 'Quantity updated successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Update failed.']);
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

        // Get low-stock raw materials
        $lowStockProducts = DB::table('product_details')
            ->where('category', 'raw materials')
            ->where('quantity', '<=', 20)
            ->where('is_archived', 0)
            ->get();

        // Generate PO number: e.g. PO-company-24071700001
        $date = now()->format('ymd');
        $sequence = str_pad(1, 5, '0', STR_PAD_LEFT); // This should increment in real app logic
        $poNumber = 'PO-company-' . $date . $sequence;

        return view('admin.purchase_order', compact('lowStockProducts', 'poNumber', 'user'));
    }
}
