<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class SalesReportController extends Controller
{
    public function AdminSalesReportPage()
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

        return view('admin.sales_report', compact('role', 'user', 'lowFinishedProducts'));
    }


    public function AdminTransactionAdd(Request $request)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin to access the dashboard.');
        }

        return redirect()->back()->with('success', 'Transaction added successfully.');
    }

    public function AdminTransactionArchive($id)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'Unauthorized access.');
        }

        DB::table('transactions')
            ->where('id', $id)
            ->update([
                'is_archived' => 1,
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Transaction archived successfully.');
    }

    public function AdminViewPrintSalesHistoryPage()
    {
        // Check if the logged-in user is an admin
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'Unauthorized access.');
        }

        $user = Auth::guard('employees')->user();

        // Get all non-archived transactions
        $transactions = DB::table('transactions')
            ->where('is_archived', 0)
            ->orderBy('transaction_date', 'desc')
            ->get();

        // Calculate total debit and credit for the report
        $totals = DB::table('transactions')
            ->where('is_archived', 0)
            ->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
            ->first();

        return view('admin.sales.print_sales', [
            'transactions' => $transactions,
            'total_debit' => $totals->total_debit ?? 0,
            'total_credit' => $totals->total_credit ?? 0,
            'user' => $user
        ]);
    }

    public function AdminValidatePinSales(Request $request)
    {
        $user = Auth::guard('employees')->user();

        if (!$user || !$request->has('pin')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ((int)$user->pin === (int)$request->input('pin')) {
            ActivityLogger::log(
                $user->id,
                'print',
                'sales_report',
                "Generate report sales transactions"
            );

            return response()->json(['message' => 'PIN verified']);
        }

        return response()->json(['message' => 'Invalid PIN'], 403);
    }
}
