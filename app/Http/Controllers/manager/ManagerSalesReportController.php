<?php

namespace App\Http\Controllers\manager;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManagerSalesReportController extends Controller
{
    public function ManagerSalesReportPage()
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 2) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an manager to access the dashboard.');
        }

        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

        $lowFinishedProducts = DB::table('product_details')
            ->where('category', 'finish product')
            ->where('quantity', '<', 1000)
            ->where('is_archived', 0)
            ->get();

        $transactions = DB::table('transactions')
            ->where('is_archived', 0)
            ->orderBy('transaction_date', 'desc')
            ->get();

        return view('manager.sales_report', compact('role', 'user', 'lowFinishedProducts', 'transactions'));
    }


    public function ManagerTransactionAdd(Request $request)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 2) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an manager to access the dashboard.');
        }

        $request->validate([
            'transaction_date' => 'required|date',
            'process_by' => 'required|string',
            'transaction_type' => 'required|string',
            'transaction_id' => 'required|string',
            'type' => 'required|in:debit,credit',
            'amount' => 'required|numeric|min:0',
        ]);

        $existing = DB::table('transactions')
            ->select(DB::raw('SUM(debit) as total_debit'), DB::raw('SUM(credit) as total_credit'))
            ->first();

        $existing_total_debit = $existing->total_debit ?? 0;
        $existing_total_credit = $existing->total_credit ?? 0;

        $new_debit = $request->type === 'debit' ? $request->amount : 0;
        $new_credit = $request->type === 'credit' ? $request->amount : 0;

        $balance = ($existing_total_credit + $new_credit) - ($existing_total_debit + $new_debit);

        DB::table('transactions')->insert([
            'transaction_date' => $request->transaction_date,
            'process_by' => $request->process_by,
            'transaction_type' => $request->transaction_type,
            'transaction_id' => $request->transaction_id,
            'debit' => $new_debit,
            'credit' => $new_credit,
            'balances' => $balance,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Transaction added successfully.');
    }

    public function ManagerTransactionArchive($id)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 2) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an manager to access the dashboard.');
        }

        DB::table('transactions')
            ->where('id', $id)
            ->update([
                'is_archived' => 1,
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Transaction archived successfully.');
    }

    public function ManagerViewPrintSalesHistoryPage()
    {
        // Check if the logged-in user is an admin
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 2) {
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

        return view('manager.sales.print_sales', [
            'transactions' => $transactions,
            'total_debit' => $totals->total_debit ?? 0,
            'total_credit' => $totals->total_credit ?? 0,
            'user' => $user
        ]);
    }

    public function ManagerValidatePinSales(Request $request)
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
