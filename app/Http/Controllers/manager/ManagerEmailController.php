<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use App\Models\Email;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ManagerEmailController extends Controller
{
    public function ManagerEmailPage()
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 2) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an manager to access the dashboard.');
        }

        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');
        $emails = Email::where('from_email', $user->email)
            ->where('is_archived', 0)
            ->latest()
            ->get();

        $suppliers = Supplier::select('supplier_email_add')->get(); // fetch emails

        return view('manager.email', compact('role', 'user', 'emails', 'suppliers'));
    }


    public function ManagerSendEmail(Request $request)
    {
        $request->validate([
            'to_email' => 'required|email',
            'subject' => 'nullable|string',
            'message' => 'nullable',
            'attachment' => 'nullable|file|max:2048',
        ]);

        $filename = null;

        if ($request->hasFile('attachment')) {
            $filename = time() . '_' . $request->file('attachment')->getClientOriginalName();
            $request->file('attachment')->move(public_path('emails'), $filename);
        }

        Email::create([
            'from_email' => Auth::guard('employees')->user()->email,
            'to_email' => $request->to_email,
            'subject' => $request->subject,
            'message' => $request->message,
            'file' => $filename,
        ]);

        Mail::raw($request->message, function ($message) use ($request, $filename) {
            $message->to($request->to_email)
                ->subject($request->subject ?? 'No Subject');

            if ($filename) {
                $message->attach(public_path("emails/{$filename}"));
            }
        });

        return redirect()->back()->with('success', 'Email sent successfully!');
    }


    public function ManagerEmailBulkDelete(Request $request)
    {
        $ids = explode(',', $request->email_ids);
        Email::whereIn('id', $ids)->update(['is_archived' => 2]);
        return redirect()->back()->with('success', 'Selected emails deleted successfully.');
    }

    public function ManagerEmailTrash()
    {
        if (!Auth::guard('employees')->check() || Auth::guard('employees')->user()->position_id != 2) {
            return redirect()->route('login.page')->with('error', 'You must be logged in as an manager to access the dashboard.');
        }

        $user = Auth::guard('employees')->user();
        $role = DB::table('positions')->where('id', $user->position_id)->value('position_name');

        $emails = Email::where('from_email', $user->email)
            ->where('is_archived', 2)
            ->latest()
            ->get();

        return view('manager.email_trash', compact('role', 'user', 'emails'));
    }

    public function ManagerEmailBulkRestore(Request $request)
    {
        $ids = explode(',', $request->email_ids);
        Email::whereIn('id', $ids)->update(['is_archived' => 0]);

        return redirect()->back()->with('success', 'Selected emails restored.');
    }

    public function ManagerEmailBulkDeletePermanent(Request $request)
    {
        $ids = explode(',', $request->email_ids);
        Email::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', 'Selected emails permanently deleted.');
    }
}
