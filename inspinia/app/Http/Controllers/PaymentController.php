<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Payment\PaymentRequestStore;
use App\Http\Requests\Payment\PaymentRequestUpdate;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\Company;
use App\Models\Contract;
use App\Models\Discount;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Movement;
use App\Models\Rate;
use App\Models\Charge;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;
use Session;
use PDF;


class PaymentController extends Controller
{
    
    var $str_description = "";
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::all();
        $company = Company::first();
        $from = (new Carbon(Session::get('from')))->format('d/m/Y');
        $to = (new Carbon(Session::get('to')))->format('d/m/Y');
        
        $payments = Payment::whereDate('date', '>=', Session::get('from'))
                            ->whereDate('date', '<=' , Session::get('to'))
                            ->orderBy('date');

        $payments_count = $payments->count();
        $payments_total = $payments->sum('amount');

        $payments = $payments->paginate(10);

        return view('payments.index')->with('payments', $payments)
                                    ->with('payments_count', $payments_count)
                                    ->with('payments_total', $payments_total)
                                    ->with('company', $company)
                                    ->with('from', $from)
                                    ->with('to', $to);  
    }

    public function change_period(Request $request){
                        
        $from = (new ToolController)->format_ymd($request->input('from'));
        $to = (new ToolController)->format_ymd($request->input('to'));

        Session::put('from', $from);
        Session::put('to', $to);
        return redirect()->route('payments.index');
    }
    
    public function edit($id){
        
        $company = Company::first();
        $payment = Payment::find(Crypt::decrypt($id));
        
        return view('payments.folio')->with('payment', $payment)
                                    ->with('company', $company);        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentRequestUpdate $request, $id)
    {
        $payment = Payment::find($id);        
        $payment->folio= $request->input('folio');
        $payment->save();
        return redirect()->route('payments.index')->with('notity', 'update');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function contracts_debt()
    {
        $company = Company::first();                  
        $contracts = Contract::all();
        //$contracts = $contracts->
        return view('payments.contracts_debt')->with('contracts', $contracts)
                                    ->with('company', $company);  
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function contracts_solvent()
    {
        $company = Company::first();                  
        $contracts = Contract::all();
        $current_year = Carbon::now()->year;
        return view('payments.contracts_solvent')->with('contracts', $contracts)
                                    ->with('current_year', $current_year)
                                    ->with('company', $company);  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($contract_id)
    {
        $payment = new Payment();
        $today = Carbon::now();        
        $contract = Contract::find(Crypt::decrypt($contract_id));
        $invoices = $contract->invoices()->where('status', 'P')->get();
        $previous_date_m2 = (new Carbon('first day of this month'))->addDays(14)->subMonths(2)->format('Y-m-t');
        $previous_date_m1 = (new Carbon('first day of this month'))->addDays(14)->subMonths(1)->format('Y-m-t');
        $previous_balance_m2 = $contract->balance_date($previous_date_m2);
        $previous_balance_m1 = $contract->balance_date($previous_date_m1);
        
        $credits_m1 = $contract->credits_range($previous_date_m2, $previous_date_m1);
        $debits_m1 = $contract->debits_range($previous_date_m2, $previous_date_m1);

        $credits = $contract->credits_from($previous_date_m1);
        $debits = $contract->debits_from($previous_date_m1);
        

        //return "Balance 2: ".$previous_balance_m2." (Creditos ".$credits_m1->sum('amount'). " - Debitos: ".$debits_m1->sum('amount'). ") Balance 1: ".$previous_balance_m1. "Creditos Mes Actual ".$credits->sum('amount')." Debitos Mes Actual ".$debits->sum('amount')." Balance Final ".$contract->balance;
        $age_discount = Discount::find(1);
        $other_discounts = Discount::where('id','>' , 1)
                                    ->where('status', 'A')->get();
        
        return view('payments.save')->with('payment', $payment)
                                    ->with('today', $today)
                                    ->with('contract', $contract)
                                    ->with('invoices', $invoices)
                                    ->with('age_discount', $age_discount)
                                    ->with('other_discounts', $other_discounts)
                                    ->with('previous_balance_m2', $previous_balance_m2)
                                    ->with('previous_balance_m1', $previous_balance_m1)
                                    ->with('balance', $contract->balance)
                                    ->with('credits_m1', $credits_m1)
                                    ->with('debits_m1', $debits_m1)
                                    ->with('credits', $credits)
                                    ->with('debits', $debits);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function future($contract_id)
    {
        $payment = new Payment();
        $today = Carbon::now(); 
        $contract = Contract::find(Crypt::decrypt($contract_id));
        $last_invoice_canceled = $contract->last_invoice_canceled;
        $last_month = $last_invoice_canceled->month;
        $last_year = $last_invoice_canceled->year;            
        //Calcular mes y año siguientes
        $carbon_next_date = (new Carbon($last_year.'-'.$last_month.'-15'))->addMonths(1);
        $initial_month =  $carbon_next_date->format('n');
        $initial_month = (strlen($initial_month)==1)?'0'.$initial_month:$initial_month;
        $year = $carbon_next_date->format('Y');
        $flat_rate = Rate::find(1);
        $iva = Charge::find(1);
        $charges = Charge::where('id','>' , 1)
                                    ->where('status', 'A')->get();        
        $age_discount = Discount::find(1);
        $other_discounts = Discount::where('id','>' , 1)
                                    ->where('type', '!=','T')
                                    ->where('status', 'A')->get();
        
        return view('payments.future')->with('payment', $payment)
                                    ->with('today', $today)
                                    ->with('contract', $contract)
                                    ->with('initial_month', $initial_month)
                                    ->with('year', $year)
                                    ->with('flat_rate', $flat_rate)
                                    ->with('age_discount', $age_discount)
                                    ->with('other_discounts', $other_discounts)
                                    ->with('iva', $iva)
                                    ->with('charges', $charges);    
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentRequestStore $request)
    {                
        $tot_debt=0;
        $tot_discount=0;
        $total =0;
        //1. Se registra el pago
        $payment = new Payment();
        $payment->date= (new ToolController)->format_ymd($request->input('date'));
        $contract = Contract::find($request->input('hdd_contract_id'));
        $tot_debt = $contract->balance;
        $payment->citizen_id = $contract->citizen->id;
        $payment->contract_id = $contract->id;
        $payment->type= $request->input('type');
        $payment->observation = $request->input('observation');
        $payment->amount =0;
        $payment->debt = $request->input('hdd_debt');
        $tot_debt= $contract->balance;
        $payment->save();
        //2. Se calcula monto del descuento si el cajero ha seleccionado alguno       
        if($request->input('hdd_discount_id')>0){    
            $discount = Discount::find($request->input('hdd_discount_id'));        
            if($discount->type == 'M'){
                $tot_discount = $discount->amount;
            }else if($discount->type == 'T'){
                $tot_discount = $tot_debt - $discount->amount;
            }else if($discount->type == 'P'){
                $tot_discount = $tot_debt * ($discount->percent/100); //Siempre se hace un descuento sobre el total de la deuda asi pague menos.
            }
            //3. Se registra el movimiento de descuento
            $movement = new Movement();
            $movement->citizen_id = $contract->citizen->id;
            $movement->contract_id = $contract->id;
            $movement->movement_type = 'D';
            $movement->type = 'D';
            $movement->payment_id = $payment->id;
            $movement->date = $payment->date;
            $movement->amount = $tot_discount;
            $movement->description = $discount->description;
            $movement->save();
            //Se registra el descuento como detalle del pago
            $payment_detail = new PaymentDetail();
            $payment_detail->payment_id = $payment->id;
            $payment_detail->type = 'D';
            $payment_detail->amount = (-1)*$tot_discount;
            $payment_detail->description = $discount->description;
            $payment_detail->save();

        }
        //4. Cancela los recibos pendientes de acuerdo al monto cancelado
        if($request->input('select_amount')=='total'){
            $this->cancel_invoices($contract, $payment, $request->input('hdd_net_debt'), $request->input('hdd_net_debt'));
        }elseif($request->input('select_amount')=='other'){
            $this->cancel_invoices($contract, $payment, $request->input('hdd_net_debt'), floatval($request->input('other_amount'))+$tot_discount);
        }        
        //5. Se actualiza el monto del pago y la deuda restante
        $payment->description = $this->str_description;
        if($request->input('select_amount')=='total'){
            $payment->amount= $tot_debt-$tot_discount;
        }else if($request->input('select_amount')=='other'){
            $payment->amount= $request->input('other_amount');
        }        
        $payment->save();
        //6. Se registra el movimiento del pago
        $movement = new Movement();
        $movement->citizen_id = $contract->citizen->id;
        $movement->contract_id = $contract->id;
        $movement->movement_type = 'D';
        $movement->type = 'P';
        $movement->payment_id = $payment->id;
        $movement->date = $payment->date;
        if($request->input('select_amount')=='total'){
            $movement->amount = $tot_debt-$tot_discount;
        }else if($request->input('select_amount')=='other'){
            $movement->amount= $request->input('other_amount');
        }        
        $movement->description = $this->str_description;
        $movement->save();
        //Se registra el monto total de los recibos como detalle del pago
        $payment_detail = new PaymentDetail();
        $payment_detail->payment_id = $payment->id;
        $payment_detail->type = 'C';
        if($request->input('select_amount')=='total'){
            $payment_detail->amount= $tot_debt;
        }else if($request->input('select_amount')=='other'){
            $payment_detail->amount= $request->input('other_amount')+$tot_discount;;
        }        
        $payment_detail->description = $this->str_description;
        $payment_detail->save();
        
        return redirect()->route('payments.preview', Crypt::encrypt($payment->id))->with('notity', 'create');
    }

    
    public function cancel_invoices($contract, $payment, $net_debt, $payment_amount){
        $months_canceled=0;
        $invoices = $contract->invoices()->where('status', 'P')->orderBy('date')->get();        
        //Si el pago es mayor o igual a la deuda neta se cancelas todos los recibos
        if(floatval($payment_amount) >= floatval($net_debt)){
            foreach ($invoices as $invoice) {
                $invoice->payment_id = $payment->id;
                $invoice->status = 'C';
                $this->str_description = $this->str_description.' '.$invoice->month.'/'.$invoice->year;            
                $invoice->save();
                $months_canceled++;
            }
            if($months_canceled==0){
                $this->str_description = 'Abono a Deuda';
            }elseif($months_canceled==1){
                $this->str_description = 'Pago Servicio de Agua Mes ('.$this->str_description.' )';
            }elseif($months_canceled>1){
                $this->str_description = 'Pago Servicio de Agua Meses ('.$this->str_description.' )';
            }        
        //Si el pago es menor se cancelan los recibos que se puedan con el monto dado por el ciudadano
        }else{
            //Cancela los recibos en orden cronologico
            foreach ($invoices as $invoice) {
                if($payment_amount >= $invoice->total){
                    $invoice->payment_id = $payment->id;
                    $invoice->status = 'C';
                    $this->str_description = $this->str_description.' '.$invoice->month.'/'.$invoice->year;            
                    $invoice->save();
                    $months_canceled++;
                    $payment_amount = $payment_amount - $invoice->total;
                }
            }
            if($months_canceled==0){
                $this->str_description = 'Abono a Deuda';
            }elseif($months_canceled==1){
                $this->str_description = 'Pago Servicio de Agua Mes ('.$this->str_description.' )';
            }elseif($months_canceled>1){
                $this->str_description = 'Pago Servicio de Agua Meses ('.$this->str_description.' )';
            }
        }
    }


    public function payment_future(Request $request){
                        
        $str_description = "";
        $tot_invoices =0;
        $tot_discount=0;
        $total =0;
        $contract = Contract::find($request->input('hdd_contract_id'));
        $initial_balance = $contract->balance;
        $flat_rate = Rate::find(1);
        $iva = Charge::find(1);
        $initial_month = intval($request->input('hdd_initial_month'));
        $final_month = intval($request->input('final_month'));

        //1. Se registra el pago
        $date = (new ToolController)->format_ymd($request->input('date'));
        $payment = new Payment();
        $payment->date= $date;
        $payment->citizen_id = $contract->citizen->id;
        $payment->contract_id = $contract->id;
        $payment->type= $request->input('type');
        $payment->observation = $request->input('observation');
        $payment->amount= 0;
        $payment->debt = 0;
        $payment->save();
        //2. Se generan los recibos del periodo selecionado (Incluye cargos e IVA).        
        for ($i= $initial_month; $i <= $final_month ; $i++) { 
            //Paso 2.1 Registrar los datos generales del recibo
            $invoice = new Invoice();
            $first_day_month = $request->input('hdd_year').'-'.$i.'-'.'1';            
            $last_day_month = date("Y-m-t", strtotime($first_day_month));
            $invoice->date = $date;
            $invoice->date_limit = $last_day_month;
            $invoice->month = (strlen($i)==1)?'0'.$i:$i;                                
            $invoice->year = $request->input('hdd_year');
            $carbon_previous_date = (new Carbon($request->input('hdd_year').'-'.$i.'-15'))->subMonths(1);
            $month_consume = $carbon_previous_date->format('n');
            $invoice->month_consume = (strlen($month_consume)==1)?'0'.$month_consume:$month_consume;         
            $invoice->year_consume = $carbon_previous_date->format('Y');
            $invoice->citizen_id = $contract->citizen->id;
            $invoice->contract_id = $contract->id;
            $invoice->rate = $flat_rate->amount;
            $invoice->rate_description = $flat_rate->name;            
            $invoice->status = 'C';
            $invoice->payment_id = $payment->id;
            $invoice->save();
            $str_description = $str_description.' '.$invoice->month.'/'.$invoice->year; 
            //Paso 2.2 Registrar el detalle del recibo
            
            //Paso 2.2.1 Incluir Cargo por Tarifa
            $invoice_detail = new InvoiceDetail();
            $invoice_detail->invoice_id = $invoice->id;              
            $invoice_detail->movement_type = 'CT';
            $invoice_detail->type = 'M';             
            $invoice_detail->description = 'Servicio de Agua '.$invoice->month.'/'.$invoice->year;            
            $invoice_detail->sub_total = $flat_rate->amount;
            $invoice_detail->save();
            //Paso 2.2.2 Incluir cargos adicionales
            if($request->input('charge')){
                foreach ($request->input('charge') as $charge_id) {
                    $charge = Charge::find($charge_id);
                    $invoice_detail = new InvoiceDetail();
                    $invoice_detail->invoice_id = $invoice->id;              
                    $invoice_detail->movement_type = $charge->movement_type;
                    $invoice_detail->type = $charge->type;                    
                    $invoice_detail->description = $charge->description;                    
                    if($charge->type=='M'){
                        $invoice_detail->sub_total = $charge->amount;
                    }elseif($charge->type=='Y'){
                        $invoice_detail->sub_total = $flat_rate->amount*($charge->percent/100);
                    }
                    $invoice_detail->save();
                }
            }
            //Paso 2.2.3 Calcular el Impuesto
            if($request->input('apply_iva') == 'Y'){
                $invoice_detail = new InvoiceDetail();
                $invoice_detail->invoice_id = $invoice->id;              
                $invoice_detail->movement_type = $iva->movement_type;
                $invoice_detail->type = $iva->type;
                $invoice_detail->description = $iva->description;
                $invoice_detail->percent = $iva->percent;
                $invoice_detail->sub_total = $invoice->total_calculated()*($iva->percent/100);
                $invoice_detail->save();
            }
            //Se calcula el monto final del recibo
            $invoice->total = $invoice->total_calculated();
            $invoice->save();
            $tot_invoices = $tot_invoices + $invoice->total;
            //3. Registrar el cargo en la tabla movimientos
            $movement = new Movement();
            $movement->date = $date;                    
            $movement->type = 'C';
            $movement->movement_type = 'C';
            $movement->description = 'Servicio de Agua '.$invoice->month.'/'.$invoice->year;
            $movement->citizen_id = $contract->citizen->id;
            $movement->contract_id = $contract->id;
            $movement->invoice_id = $invoice->id;
            $movement->amount = $invoice->total;
            $movement->save();        
        }        
        // end for

        //4. Se calcula monto del descuento
        $discount = Discount::find($request->input('hdd_discount_id'));
        if($discount){
            if($discount->type == 'M'){
                $tot_discount = $discount->amount;
            }else if($discount->type == 'P'){
                $tot_discount = $tot_invoices * ($discount->percent/100);
            }
            //5. Se registra el movimiento de descuento
            $movement = new Movement();
            $movement->citizen_id = $contract->citizen->id;
            $movement->contract_id = $contract->id;
            $movement->movement_type = 'D';
            $movement->type = 'D';
            $movement->payment_id = $payment->id;
            $movement->date = $date;
            $movement->amount = $tot_discount;
            $movement->description = $discount->description;
            $movement->save();
            //Se registra el descuento como detalle del pago
            $payment_detail = new PaymentDetail();
            $payment_detail->payment_id = $payment->id;
            $payment_detail->type = 'D';
            $payment_detail->amount = (-1)*$tot_discount;
            $payment_detail->description = $discount->description;
            $payment_detail->save();                            
        }
        //Se actualiza el monto final del pago
        $str_description = 'Pago Adelantado Servicio de Agua Meses ('.$str_description.' )';
        $payment->amount = $initial_balance + $tot_invoices - $tot_discount;
        $payment->description = $str_description;
        $payment->save();

        //6. Se registra el movimiento del pago
        $movement = new Movement();
        $movement->citizen_id = $contract->citizen->id;
        $movement->contract_id = $contract->id;
        $movement->movement_type = 'D';
        $movement->type = 'P';
        $movement->payment_id = $payment->id;
        $movement->date = $date;
        $final_balance = $initial_balance + $tot_invoices - $tot_discount;
        if($final_balance > 0){
            $movement->amount = $final_balance;
        }else{
           $movement->amount = 0; //Toma el dinero del saldo a favor 
        }
        $movement->description = $str_description;
        $movement->save();
        //Se registra el monto total de los recibos como detalle del pago
        $payment_detail = new PaymentDetail();
        $payment_detail->payment_id = $payment->id;
        $payment_detail->type = 'C';
        $payment_detail->amount = $initial_balance + $tot_invoices;
        $payment_detail->description = $str_description;
        $payment_detail->save();
    
        return redirect()->route('payments.preview', Crypt::encrypt($payment->id))->with('notity', 'create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function preview($id)
    {
        $company = Company::first();
        $payment = Payment::find(Crypt::decrypt($id)); 
        return view('payments.preview')->with('payment', $payment)
                                        ->with('company', $company);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $company = Company::first();
        $payment = Payment::find(Crypt::decrypt($id)); 
        return view('payments.show')->with('payment', $payment)
                                        ->with('company', $company);
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
        * Logica de eliminacion de un pago
        */        
        $payment = Payment::find($id);
        //1. Se eliminam los movimientos asociados al pago.
        $payment->movements()->delete();        
        //2. Se elimina el detalle del pago
        $payment->payment_details()->delete();
        //3. Se cambia el estatus de los recibos cancelados (status P=Pendiente, deuda anterior 0,  payment_id=NULL)
        $payment->invoices()->update(array('status' => 'P', 'previous_debt' => 0, 'payment_id' => null));
        //4. Se elimina el pago
        $payment->delete();

        return redirect()->route('payments.index')->with('notity', 'delete');


    }

    /**
     * Update the status to specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status($id)
    {
        $payment = Payment::find(Crypt::decrypt($id));
        ($payment->status == "A")?$payment->status="D":$payment->status= "A";  
        $payment->save();
        return redirect()->route('payments.index');
    }

    /*
     * Download file from DB  
    */     
    public function report_period(Request $request)
    {
        $company = Company::first();
        $from = (new Carbon(Session::get('from')))->format('d/m/Y');
        $to = (new Carbon(Session::get('to')))->format('d/m/Y');
        
        $payments = Payment::whereDate('date', '>=', Session::get('from'))
                            ->whereDate('date', '<=' , Session::get('to'))
                            ->orderBy('date')->get();
        
        $payments_by_municipality = DB::table('payments')
                                    ->join('contracts', 'payments.contract_id', '=', 'contracts.id')
                                    ->join('municipalities', 'contracts.municipality_id', '=', 'municipalities.id')
                                    ->select('municipalities.name as municipality', DB::raw('sum(payments.amount) as amount'))
                                    ->whereDate('payments.date', '>=', Session::get('from'))
                                    ->whereDate('payments.date', '<=' , Session::get('to'))
                                    ->groupBy('municipality_id')
                                    ->orderBy('payments.date')
                                    ->get();        
        
        $data=[
            'company' => $company,
            'payments' => $payments,
            'from' => $from,
            'to' => $to,
            'payments_by_municipality' => $payments_by_municipality,
            'logo' => 'data:image/png;base64, '.$company->logo 
        ];
        $pdf = PDF::loadView('reports/payments_period', $data);
        return $pdf->download('Pagos desde '.$from.' hasta '.$to.'.pdf');

    }

}
