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
                
        //Recibos del Año
        $invoices_year = Invoice::whereYear('date', '=', $current_year)
                                ->where('total','>' ,0)->get();
        
        $sum_invoices_year = $invoices_year->sum('total');        
        $count_invoices_year = $invoices_year->count();
        
        $sum_invoices_year_pending = $invoices_year->where('status', 'P')->sum('total');
        $count_invoices_year_pending = $invoices_year->where('status', 'P')->count();
        $sum_invoices_year_canceled = $invoices_year->where('status', 'C')->sum('total');
        $count_invoices_year_canceled = $invoices_year->where('status', 'C')->count();
        
        //Recibos del Mes
        $invoices_month = Invoice::whereYear('date', '=', $current_year)
                            ->whereMonth('date', '=', $current_month)
                            ->where('total','>' ,0)->get();
        
        $sum_invoices_month = $invoices_month->sum('total');        
        $count_invoices_month = $invoices_month->count();

        $sum_invoices_month_pending =  $invoices_month->where('status', 'P')->sum('total');
        $count_invoices_month_pending =  $invoices_month->where('status', 'P')->count();
        $sum_invoices_month_canceled =  $invoices_month->where('status', 'C')->sum('total');
        $count_invoices_month_canceled =  $invoices_month->where('status', 'C')->count();

        //Pagos del Año
        $payments_year = Payment::whereYear('date', '=', $current_year)->get();        
        
        $sum_payments_year = $payments_year->sum('amount');
        $count_payments_year = $payments_year->count();

        //Pagos del Mes
        $payments_month = Payment::whereYear('date', '=', $current_year)
                            ->whereMonth('date', '=', $current_month)->get();
        
        $sum_payments_month = $payments_month->sum('amount');
        $count_payments_month = $payments_month->count();

        //Descuentos del Año
        $discounts_year = Movement::where('movement_type', 'D')
                            ->where('type', 'D')
                            ->whereYear('date', '=', $current_year)->get();
        
        $sum_discounts_year = $discounts_year->sum('amount');
        $count_discounts_year = $discounts_year->count();

        //Descuentos del Mes
        $discounts_month = Movement::where('movement_type', 'D')
                            ->where('type', 'D')
                            ->whereYear('date', '=', $current_year)
                            ->whereMonth('date', '=', $current_month)->get();        
        
        $sum_discounts_month = $discounts_month->sum('amount');
        $count_discounts_month = $discounts_month->count();
        

        $total_invoices_year = $this->invoices_data_set($current_year);
        $total_incomes_year = $this->incomes_data_set($current_year);        
        
        $ticks_neighborhood = $this->ticks_neighborhood();        
        $incomes_by_neighborhood = $this->incomes_by_neighborhood($current_year);
        $invoices_by_neighborhood = $this->invoices_by_neighborhood($current_year);

        return view('home')->with('current_year', $current_year)
                            ->with('current_month', $current_month)
                            //Pagos
                            ->with('sum_payments_year', $sum_payments_year)
                            ->with('count_payments_year', $count_payments_year)
                            ->with('sum_payments_month', $sum_payments_month)
                            ->with('count_payments_month', $count_payments_month)
                            //Descuentos
                            ->with('sum_discounts_year', $sum_discounts_year)
                            ->with('count_discounts_year', $count_discounts_year)
                            ->with('sum_discounts_month', $sum_discounts_month)
                            ->with('count_discounts_month', $count_discounts_month)
                            //Recibos
                            ->with('count_invoices_month', $count_invoices_month)
                            ->with('sum_invoices_month_pending', $sum_invoices_month_pending)
                            ->with('count_invoices_month_pending', $count_invoices_month_pending)
                            ->with('sum_invoices_year_pending', $sum_invoices_year_pending)
                            ->with('count_invoices_year_pending', $count_invoices_year_pending)
                            //Otros
                            ->with('total_invoices_year', $total_invoices_year)
                            ->with('total_incomes_year', $total_incomes_year)
                            ->with('ticks_neighborhood', $ticks_neighborhood)
                            ->with('incomes_by_neighborhood', $incomes_by_neighborhood)
                            ->with('invoices_by_neighborhood', $invoices_by_neighborhood);

    }


    public function invoices_data_set($current_year){
        $output="";        
        for ($i= 1; $i <= 12; $i++) {
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


    public function ticks_neighborhood(){
        $output="";
        $i=1;
        $contracts = Contract::groupBy('neighborhood')->get(); 
        $max_i =  $contracts->count();       
        foreach ($contracts as $contract) {
            if($i < $max_i){
                $output = $output.'['.$i++.',`'.$contract->neighborhood.'`],';
            }else{
                $output = $output.'['.$i++.',`'.$contract->neighborhood.'`]';                
            }
        }
        $output = '['.$output.']';
        return $output;
    }
    
    public function invoices_by_neighborhood($current_year){
        $output="";
        $i=1;
        $contracts = Contract::groupBy('neighborhood')->get(); 
        $max_i =  $contracts->count();       
        foreach ($contracts as $contract) {
            $invoices = Invoice::whereYear('date', '=', $current_year)
                                ->whereHas('Contract', 
                                function($q) use ($contract){
                                    $q->where('neighborhood', $contract->neighborhood);
                                })->get();                

            $total_month = $invoices->sum('total');
            if($total_month==''){$total_month=0;}
            if($i<$max_i){
                $output = $output.'['.$i++.','.$total_month.'],';
            }else{
                $output = $output.'['.$i++.','.$total_month.']';                
            }
        }
        $output = '['.$output.']';
        return $output;
    }


    public function incomes_by_neighborhood($current_year){
        $output="";
        $i=1;
        $contracts = Contract::groupBy('neighborhood')->get(); 
        $max_i =  $contracts->count();       
        foreach ($contracts as $contract) {
            //Pagos (no incluye descuentos)
            $movements = Movement::whereYear('date', '=', $current_year)
                                ->where('movement_type', 'D')
                                ->where('type', 'P')
                                ->whereHas('Contract', 
                                function($q) use ($contract){
                                    $q->where('neighborhood', $contract->neighborhood);
                                })->get();
            $total_month = $movements->sum('amount');
            if($total_month==''){$total_month=0;}
            if($i<$max_i){
                $output = $output.'['.$i++.','.$total_month.'],';
            }else{
                $output = $output.'['.$i++.','.$total_month.']';                
            }
        }
        $output = '['.$output.']';
        return $output;
    }

}
