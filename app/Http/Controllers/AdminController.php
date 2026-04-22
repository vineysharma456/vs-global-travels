<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VisaApplication;
use DB;
class AdminController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            if (Auth::user()->is_admin) {
                return redirect()->route('admin.dashboard');
            }

            Auth::logout();
            return back()->with('error', 'You are not an admin');
        }

        return back()->with('error', 'Invalid credentials');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }

    public function adminDashboard(){

       $pendingPayments =VisaApplication::where('payment_status','pending')->count();
       $application =VisaApplication::where('payment_status','paid')->count();
        return view('admin.dashboard',compact('pendingPayments','application'));
    }

    public function visaApplications(){
         $data = DB::table('visa_applications as va')
                ->leftJoin('countries as c', 'va.country_id', '=', 'c.id')
                ->leftJoin('application_travellers as t', 'va.id', '=', 't.visa_application_id')
                ->leftJoin('application_documents as d', 't.id', '=', 'd.application_travellers') // FIX COLUMN NAME
                ->select(
                    'va.id as app_id',
                    'va.application_ref',
                    'va.payment_status',
                    'c.country_name',
                    't.id as traveller_id',
                    't.full_name',
                    't.passport_number',
                    'd.doc_type',
                    'd.file_path'
                )
                  ->orderBy('va.created_at', 'desc') // 👈 THIS LINE
                ->get();
          // ✅ GROUP DATA (UNIQUE APPLICATION)
         $applications = $data->groupBy('app_id');
      

   return view('admin.visa_applications.pending-payments', compact('applications'));
    }
}