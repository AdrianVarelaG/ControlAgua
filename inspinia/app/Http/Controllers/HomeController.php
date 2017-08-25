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
use App\Models\Movement;
use Carbon\Carbon;
use Session;
use DB;

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
        $current_month = Carbon::now()->month;
        $citizens = Citizen::all();
        $contracts = Contract::all();
        //Pagos
        $payments = Movement::where('movement_type', 'D')
                            ->where('type', 'P')
                            ->whereYear('date', '=', $current_year)
                            ->whereMonth('date', '=', $current_month)->get();
        //Descuentos
        $discounts = Movement::where('movement_type', 'D')
                            ->where('type', 'D')
                            ->whereYear('date', '=', $current_year)
                            ->whereMonth('date', '=', $current_month)->get();        
        //Recibos del Mes
        $invoices_month = Invoice::whereYear('date', '=', $current_year)
                            ->whereMonth('date', '=', $current_month)->get();

        //Recibos del Año
        $invoices_year = Invoice::whereYear('date', '=', $current_year)->get();


        $total_invoices_year = $this->invoices_data_set($current_year);
        $total_incomes_year = $this->incomes_data_set($current_year);        
        
        return view('home')->with('citizens', $citizens)
                            ->with('contracts', $contracts)
                            ->with('current_year', $current_year)
                            ->with('current_month', $current_month)
                            ->with('payments', $payments)
                            ->with('discounts', $discounts)
                            ->with('invoices_month', $invoices_month)
                            ->with('invoices_year', $invoices_year)
                            ->with('total_invoices_year', $total_invoices_year)
                            ->with('total_incomes_year', $total_incomes_year);
    }


    public function invoices_data_set($current_year){
        $output="";        
        for ($i= 1; $i <= 12; $i++) {
            //Costos
            $total_month = Invoice::whereYear('date', '=', $current_year)
                                    ->whereMonth('date', '=', $i)->sum('total');

            if($total_month==''){$total_month=0;}
            if($i<12){
                $output = $output.'['.$i.','.$total_month.'],';
            }else{
                $output = $output.'['.$i.','.$total_month.']';                
            }
        }
        $output = '['.$output.']';
        return $output;
    }

    public function incomes_data_set($current_year){
        $output="";        
        for ($i= 1; $i <= 12; $i++) {
            //Pagos (no incluye descuentos)
            $total_month = Movement::where('movement_type', 'D')
                            ->where('type', 'P')
                            ->whereYear('date', '=', $current_year)
                            ->whereMonth('date', '=', $i)->sum('amount');

            if($total_month==''){$total_month=0;}
            if($i<12){
                $output = $output.'['.$i.','.$total_month.'],';
            }else{
                $output = $output.'['.$i.','.$total_month.']';                
            }
        }
        $output = '['.$output.']';
        return $output;
    }


}
