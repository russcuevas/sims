<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArchiveController extends Controller
{
    public function AdminArchivePage()
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin to access the dashboard.');
        }

        // Fetch logged-in user and role
        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

        // fetch notification finish products
        $lowFinishedProducts = DB::table('product_details')
            ->where('category', 'finish product')
            ->where('quantity', '<', 1000)
            ->where('is_archived', 0)
            ->get();

        $archivedEmployees = DB::table('employees')
            ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
            ->leftJoin('contracts', 'employees.contract_id', '=', 'contracts.id')
            ->select(
                'employees.*',
                'positions.position_name',
                'contracts.contract'
            )
            ->where('employees.is_archived', 1)
            ->get();


        return view('admin.archive', compact('role', 'user', 'lowFinishedProducts', 'archivedEmployees'));
    }

    public function AdminRestoreEmployee($id)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin to access the dashboard.');
        }

        $employee = Employee::findOrFail($id);
        $employee->is_archived = 0;
        $employee->save();

        return redirect()->back()->with('success', 'Employee restored successfully.');
    }



    public function AdminArchiveStocksPage()
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin to access the dashboard.');
        }

        // Fetch logged-in user and role
        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

        // fetch notification finish products
        $lowFinishedProducts = DB::table('product_details')
            ->where('category', 'finish product')
            ->where('quantity', '<', 1000)
            ->where('is_archived', 0)
            ->get();

        $archivedStocks = DB::table('product_details')
            ->where('is_archived', 1)
            ->get();

        return view('admin.archive_stocks', compact('role', 'user', 'lowFinishedProducts', 'archivedStocks'));
    }

    public function AdminRestoreStocks($id)
    {
        DB::table('product_details')->where('id', $id)->update(['is_archived' => 0]);
        return redirect()->back()->with('success', 'Stock item restored successfully.');
    }

    public function AdminArchiveStockInPage()
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin to access the dashboard.');
        }

        // Fetch logged-in user and role
        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

        // fetch notification finish products
        $lowFinishedProducts = DB::table('product_details')
            ->where('category', 'finish product')
            ->where('quantity', '<', 1000)
            ->where('is_archived', 0)
            ->get();

        $archivedStockIns = DB::table('history_raw_materials')
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
            ->where('history_raw_materials.is_archived', 1)
            ->orderBy('history_raw_materials.created_at', 'desc')
            ->get();

        // group by transaction ID
        $historyGroups = $archivedStockIns->groupBy('transact_id');

        return view('admin.archive_stock_in', compact('role', 'user', 'lowFinishedProducts', 'historyGroups'));
    }

    public function AdminRestoreStockIn($transact_id)
    {
        DB::table('history_raw_materials')
            ->where('transact_id', $transact_id)
            ->update(['is_archived' => 0]);

        return redirect()->back()->with('success', 'Stock-in record restored successfully.');
    }
    public function AdminArchiveProcessPage()
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin to access the dashboard.');
        }

        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

        $lowFinishedProducts = DB::table('product_details')
            ->where('category', 'finish product')
            ->where('quantity', '<', 1000)
            ->where('is_archived', 0)
            ->get();

        $archivedHistory = DB::table('history_finish_products')
            ->where('is_archived', 1)
            ->orderBy('process_date', 'desc')
            ->get()
            ->groupBy('transact_id');

        return view('admin.archive_process', compact(
            'role',
            'user',
            'lowFinishedProducts',
            'archivedHistory'
        ));
    }

    public function AdminRestoreProcess($transact_id)
    {
        DB::table('history_finish_products')
            ->where('transact_id', $transact_id)
            ->update(['is_archived' => 0]);

        return redirect()->back()->with('success', 'Process history restored successfully.');
    }

    public function AdminArchiveDeliveryPage()
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin to access the dashboard.');
        }

        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

        $deliveryOrders = DB::table('delivery_orders')
            ->where('is_archived', 1)
            ->orderBy('transaction_date', 'desc')
            ->get()
            ->groupBy('transact_id');

        $lowFinishedProducts = DB::table('product_details')
            ->where('category', 'finish product')
            ->where('quantity', '<', 1000)
            ->where('is_archived', 0)
            ->get();

        return view('admin.archive_delivery', compact(
            'role',
            'user',
            'lowFinishedProducts',
            'deliveryOrders'
        ));
    }

    public function AdminRestoreDelivery($transact_id)
    {
        DB::table('delivery_orders')
            ->where('transact_id', $transact_id)
            ->update(['is_archived' => 0]);

        return redirect()->back()->with('success', 'Delivery order restored successfully.');
    }

    public function AdminArchiveSalesPage()
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin to access the dashboard.');
        }

        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

        $lowFinishedProducts = DB::table('product_details')
            ->where('category', 'finish product')
            ->where('quantity', '<', 1000)
            ->where('is_archived', 0)
            ->get();

        $transactions = DB::table('transactions')
            ->where('is_archived', 1)
            ->orderBy('transaction_date', 'desc')
            ->get();

        return view('admin.archive_sales', compact(
            'role',
            'user',
            'lowFinishedProducts',
            'transactions',
        ));
    }

    public function AdminRestoreSales($id)
    {
        DB::table('transactions')->where('id', $id)->update(['is_archived' => 0]);
        return redirect()->back()->with('success', 'Sales transaction restored successfully.');
    }
}
