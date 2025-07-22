<?php

namespace App\Http\Controllers\admin;

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

        $transactions = DB::table('transactions')
            ->where('is_archived', 0)
            ->orderBy('transaction_date', 'desc')
            ->get();

        return view('admin.sales_report', compact('role', 'user', 'lowFinishedProducts', 'transactions'));
    }


    public function AdminTransactionAdd(Request $request)
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 1) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an admin to access the dashboard.');
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
}
