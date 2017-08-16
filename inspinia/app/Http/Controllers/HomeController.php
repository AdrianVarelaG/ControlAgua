<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Company;
use App\Models\Citizen;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\Invoice;
use Carbon\Carbon;
use Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $current_year = Carbon::now()->year;
        $current_month = (strlen(Carbon::now()->month)==1)?'0'.Carbon::now()->month:Carbon::now()->month;
        $citizens = Citizen::all();
        $contracts = Contract::all();
        $payments = Payment::whereYear('date', '=', $current_year)
                            ->whereMonth('date', '=', $current_month);
        $invoices = Invoice::where('year', '=', $current_year)
                            ->where('month', '=', $current_month);
        return view('home')->with('citizens', $citizens)
                            ->with('contracts', $contracts)
                            ->with('current_year', $current_year)
                            ->with('current_month', $current_month)
                            ->with('payments', $payments)
                            ->with('invoices', $invoices);
    }
}
