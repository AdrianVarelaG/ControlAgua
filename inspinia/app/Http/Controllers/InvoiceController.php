<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Invoice\InvoiceRequestStore;
use App\Http\Requests\Invoice\InvoiceRequestUpdate;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\InvoiceSetting;
use App\Models\Company;
use App\Models\Contract;
use App\Models\Reading;
use App\Models\Rate;
use App\Models\Routine;
use App\Models\Charge;
use App\Models\Movement;
use Illuminate\Support\Facades\Crypt;
use DB;
use Auth;


class InvoiceController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($year, $month)
    {
        $invoices = Invoice::whereYear('date', '=', Crypt::decrypt($year))
                            ->whereMonth('date', '=', Crypt::decrypt($month))->get();
        $company = Company::first();          
        return view('invoices.index')->with('invoices', $invoices)
                                    ->with('company', $company)
                                    ->with('year', Crypt::decrypt($year))
                                    ->with('month', Crypt::decrypt($month));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_group()
    {
        $company = Company::first();                  
        $invoices_groups  = DB::table('invoices')
                                ->select('year_consume', 'month_consume', 'year', 'month', DB::raw('count(id) as tot_invoice'), DB::raw('sum(total) as tot_amount'))
                                ->groupby('month')
                                ->groupby('year')
                                ->orderby('year', 'DESC')
                                ->orderby('month', 'DESC')->get();
        
        return view('invoices.index_group')->with('invoices_groups', $invoices_groups)
                                    ->with('company', $company);     
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function routines()
    {        
        $routines_generated = Routine::all();
        $company = Company::first();          
        return view('invoices.routines')->with('routines_generated', $routines_generated)
                                    ->with('company', $company); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generate()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $invoice = new Invoice();
        $flat_rate = Rate::find(1);
        $iva = Charge::find(1);        
        $charges = Charge::where('id', '>', 1)->where('status', 'A')->get();
        $last_day_month = date("t/m/Y");
        return view('invoices.generate')->with('charges', $charges)
                                        ->with('flat_rate', $flat_rate)
                                        ->with('iva', $iva)
                                        ->with('last_day_month', $last_day_month)
                                        ->with('invoice', $invoice);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvoiceRequestStore $request)
    {
        $flat_rate = Rate::find(1);
        $iva = Charge::find(1);
        $amount_consume =0;
        $month_consume = substr($request->input('date_consume'),0,2);
        $year_consume = substr($request->input('date_consume'),3,4);
        if(!$this->routine_exist($year_consume, $month_consume)){
            //Paso 1. Hacer el ciclo con todos los contratos ACTIVOS
            $contracts = Contract::where('status', 'A')->get();
            foreach($contracts as $contract){            
                //Crea el recibo si no existe uno previo para ese periodo
                $invoice_exist = $contract->invoices()->where('year_consume', $year_consume)
                                                    ->where('month_consume', $month_consume)->first();
                if(!$invoice_exist){
                    //Paso 2. Registrar los datos generales del recibo
                    $invoice = new Invoice();
                    $invoice->date = (new ToolController)->format_ymd($request->input('date'));
                    $invoice->date_limit = (new ToolController)->format_ymd($request->input('date_limit'));
                    $invoice->year = substr($request->input('date'),6,4);
                    $invoice->month = substr($request->input('date'),3,2);                    
                    $invoice->month_consume = $month_consume;
                    $invoice->year_consume = $year_consume;
                    $invoice->citizen_id = $contract->citizen->id;
                    $invoice->contract_id = $contract->id;
                    $invoice->message = $request->input('message');
                    $invoice->status = 'P';
                    $invoice->save();
                    //Paso 3. Registrar el detalle del recibo
            
                    //Paso 3.1 Incluir Tarifas
                    $invoice_detail = new InvoiceDetail();
                    $invoice_detail->invoice_id = $invoice->id;              
                    $invoice_detail->movement_type = 'CT';
                    $invoice_detail->type = 'M';             
                    $invoice_detail->description = 'Servicio de Agua';            
                    if ($request->input('type')=='F'){
                        $invoice->rate = $flat_rate->amount;
                        $invoice->rate_description = $flat_rate->name;
                        $amount_consume =  $flat_rate->amount;
                        $invoice_detail->sub_total = $amount_consume;
                    }
                    elseif($request->input('type')=='C'){
                        //Si tiene lectura en ese mes calcula sino se coloca la tarifa fija
                        //$reading = new Reading();
                        $reading = $contract->readings()->where('year', $year_consume)
                                                        ->where('month', $month_consume)->first();
                        if($reading){
                            $invoice->rate = $contract->rate->amount;
                            $invoice->rate_description = $contract->rate->name;
                            $invoice->reading_id = $reading->id;
                            $amount_consume = $reading->consume*$contract->rate->amount; 
                        }else{
                            $invoice->rate = $flat_rate->amount;
                            $invoice->rate_description = $flat_rate->name.' (Sin lectura)';
                            $amount_consume = $flat_rate->amount;                    
                        }
                        $invoice_detail->sub_total = $amount_consume;
                    }
                    $invoice_detail->save();
                    //Paso 3.2 Incluir cargos adicionales monto fijo
                    if($request->input('charges_m')){
                        foreach ($request->input('charges_m') as $charges_m) {
                            $charge = Charge::find($charges_m);
                            $invoice_detail = new InvoiceDetail();
                            $invoice_detail->invoice_id = $invoice->id;              
                            $invoice_detail->movement_type = $charge->movement_type;
                            $invoice_detail->type = 'M';
                            $invoice_detail->description = $charge->description;
                            $invoice_detail->sub_total = $charge->amount;
                            $invoice_detail->save();
                        }
                    }
                    //Paso 3.3 Incluir cargos adicionales porcentuales
                    if($request->input('charges_p')){
                        foreach ($request->input('charges_p') as $charges_p) {
                            $charge = Charge::find($charges_p);
                            $invoice_detail = new InvoiceDetail();
                            $invoice_detail->invoice_id = $invoice->id;              
                            $invoice_detail->movement_type = $charge->movement_type;
                            $invoice_detail->type = $charge->type;
                            $invoice_detail->description = $charge->description;
                            $invoice_detail->percent = $charge->percent;
                            $invoice_detail->sub_total = $amount_consume*($charge->percent/100);
                            $invoice_detail->save();
                        }
                    }
                    //Paso4. Calcular el Impuesto
                    if($request->input('iva')){
                            $invoice_detail = new InvoiceDetail();
                            $invoice_detail->invoice_id = $invoice->id;              
                            $invoice_detail->movement_type = $iva->movement_type;
                            $invoice_detail->type = $iva->type;
                            $invoice_detail->description = $iva->description;
                            $invoice_detail->percent = $iva->percent;
                            $invoice_detail->sub_total = $invoice->total_calculated()*($iva->percent/100);
                            $invoice_detail->save();
                    }
                    //Paso5. Actualizar el monto total del recibo en la tabla padre
                    $invoice->total = $invoice->total_calculated();
                    $invoice->save();
                    //Paso6. Registrar el cargo en la tabla movimientos
                    $movement = new Movement();
                    $movement->date = $invoice->date;                    
                    $movement->type = 'C';
                    $movement->movement_type = 'C';
                    $movement->description = 'Servicio de Agua '.$invoice->month.'/'.$invoice->year;
                    $movement->citizen_id = $contract->citizen->id;
                    $movement->contract_id = $contract->id;
                    $movement->invoice_id = $invoice->id;
                    $movement->amount = $invoice->total;
                    $movement->save();
                }
            }
                
            //Paso5. Registrar la rutina en la tabla control (routines)
            $routine = new Routine();
            $routine->year = substr($request->input('date'),6,4);
            $routine->month = substr($request->input('date'),3,2);
            $routine->year_consume = $year_consume;
            $routine->month_consume = $month_consume;
            $routine->rate_type = $request->input('type');
            $routine->created_by = Auth::user()->name;
            $routine->save();
        
            return redirect()->route('invoices.routines')->with('notity', 'create');;

        }else{
            return redirect()->route('invoices.create')->withErrors(array('global' => 'Existe una generación de recibos previa para el mes '.$month_consume.' y el año '.$year_consume. '. Primero debe reversar la generación anterior para poder realizar una nueva generación de recibos.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Invoice::find(Crypt::decrypt($id));
        $company = Company::first();          
        
        return view('invoices.show')->with('invoice', $invoice)
                                        ->with('company', $company);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InvoiceRequestUpdate $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reverse_routine($year, $month)
    {
        //Paso1. Se eliminan los recibos pendientes (no los cancelados)
        $invoices = Invoice::where('year_consume', Crypt::decrypt($year))
                            ->where('month_consume','=', Crypt::decrypt($month))
                            ->where('status','P');
        $invoices->delete();
        //Pas2. Se elimina el registro de la tabla control (routines)
        $routine = Routine::where('year_consume', Crypt::decrypt($year))
                            ->where('month_consume','=', Crypt::decrypt($month));
        $routine->delete();
        return redirect()->route('invoices.routines')->with('notity', 'delete');   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /**
        * Logica de eliminacion.
        */        
        $invoice = Invoice::find($id);
        $year = $invoice->year;
        $month = $invoice->month;
        
        if ($invoice->status == 'P'){            
            $state->delete();
            return redirect()->route('invoices.index', [Crypt::encrypt($year), Crypt::encrypt($month)])->with('notity', 'delete');        
        }else{            
        return redirect()->route('invoices.index', [Crypt::encrypt($year), Crypt::encrypt($month)])->withErrors('No se pueden elminar recibos que ya han sido cancelados.');
        }
    }

    public function routine_exist($year_consume, $month_consume){
        $routine = Routine::where('year_consume', $year_consume)
                        ->where('month_consume', $month_consume);
        if($routine->count()){
            return true;
        }else{
            return false;
        }

    }

}
