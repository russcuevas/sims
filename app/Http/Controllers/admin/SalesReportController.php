<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\SalesTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class SalesReportController extends Controller
{
    public function AdminSalesReportPage()
    {
        // Check if logged in and role is Admin (position_id = 1)
        $user = Auth::guard('employees')->user();
        if (!$user || $user->position_id != 1) {
            return redirect()->route('login.page')
                ->with('error', 'You must be logged in as an admin to access the dashboard.');
        }

        // Get role name
        $role = DB::table('positions')
            ->where('id', $user->position_id)
            ->value('position_name');

        // Low stock finished products (< 1000)
        $lowFinishedProducts = DB::table('product_details')
            ->where('category', 'finish product')
            ->where('quantity', '<', 1000)
            ->where('is_archived', 0)
            ->get();

        // Transactions by type
        $stockInSalesTransaction = DB::table('sales_transactions')
            ->where('transaction_type', 'stock in')
            ->orderByDesc('transaction_date')
            ->get();

        $deliveryTransactions = DB::table('sales_transactions')
            ->where('transaction_type', 'delivery')
            ->orderByDesc('transaction_date')
            ->get();

        $allSalesTransactions = DB::table('sales_transactions')
            ->whereIn('transaction_type', ['delivery', 'payment', 'return-item'])
            ->orderByDesc('transaction_date')
            ->get();

        // Collect unique transaction IDs from stock-in and delivery
        $transactionIds = $stockInSalesTransaction->pluck('transaction_id')
            ->merge($deliveryTransactions->pluck('transaction_id'))
            ->unique();

        return view('admin.sales_report', compact(
            'role',
            'user',
            'lowFinishedProducts',
            'stockInSalesTransaction',
            'deliveryTransactions',
            'allSalesTransactions',
            'transactionIds'
        ));
    }

    public function AdminTransactionAdd(Request $request)
    {
        // Ensure the user is authenticated and is an admin
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin to access the dashboard.');
        }

        // Validate the incoming request
        $request->validate([
            'transaction_date' => 'required|date',
            'transaction_type' => 'required|in:payment,return-item',
            'transaction_id' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        // Initialize fields
        $credit = 0;
        $debit = 0;
        $loss = 0;

        // Determine the credit, debit, and loss based on transaction type
        if ($request->transaction_type === 'payment') {
            $credit = $request->amount;
        } elseif ($request->transaction_type === 'return-item') {
            $credit = $request->amount;
            $loss = $request->amount; // Assuming loss equals the return amount
        }

        // Create a new transaction record
        SalesTransaction::create([
            'transaction_date' => $request->transaction_date,
            'process_by' => Auth::guard('employees')->user()->employee_firstname . ' ' . Auth::guard('employees')->user()->employee_lastname,
            'transaction_type' => $request->transaction_type,
            'transaction_id' => $request->transaction_id,
            'payment' => $request->transaction_type === 'payment' ? $request->amount : 0,
            'return' => $request->transaction_type === 'return-item' ? $request->amount : 0,
            'credit' => $credit,
            'debit' => $debit,
            'loss' => $loss,
            'balances' => 0, // Set the initial balance as needed
        ]);

        // Redirect back with a success message
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
