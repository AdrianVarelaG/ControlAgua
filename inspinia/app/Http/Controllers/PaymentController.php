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


class PaymentController extends Controller
{
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($period)
    {
        $payments = Payment::all();
        $company = Company::first();          
        
        switch ($period) 
        {
            case '1':
                $period_title = 'Pagos último mes';
                break;            
            case '3':
                $period_title = 'Pagos últimos 3 meses';
                break;
            case '6':
                $period_title = 'Pagos últimos 6 meses';
                break;
            case '12':
                $period_title = 'Pagos últimos 12 meses';
                break;
            case 'all':
                $period_title = 'Todos los Pagos';
                break;
        }        
        if($period == '1'){
            $payments = Payment::whereYear('date','=',Carbon::now()->year)
                                ->whereMonth('date','=',Carbon::now()->month)
                                ->orderBy('date')->get();
        }
        else if($period == '3'){
            $initial_date = Carbon::now()->subMonths(2)->startOfMonth();
            $payments = Payment::where('date','>=',$initial_date)
                                    ->orderBy('date')->get();
        }        
        else if($period == '6'){
            $initial_date = Carbon::now()->subMonths(5)->startOfMonth();
            $payments = Payment::where('date','>=',$initial_date)
                                    ->orderBy('date')->get();
        }
        else if($period == '12'){
            $initial_date = Carbon::now()->subMonths(11)->startOfMonth();
            $payments = Payment::where('date','>=',$initial_date)
                                    ->orderBy('date')->get();
        }
        else if($period == 'all'){
            $initial_date = Carbon::now();
            $payments = Payment::orderBy('date')->get();
        }

        return view('payments.index')->with('payments', $payments)
                                    ->with('company', $company)
                                    ->with('period', $period) 
                                    ->with('period_title', $period_title);  
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
        //$contracts = $contracts->
        return view('payments.contracts_solvent')->with('contracts', $contracts)
                                    ->with('company', $company);  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($contract_id)
    {
        $payment = new Payment();
        $contract = Contract::find(Crypt::decrypt($contract_id));
        $invoices = $contract->invoices()->where('status', 'P')->get();
        $age_discount = Discount::find(1);
        $other_discounts = Discount::where('id','>' , 1)
                                    ->where('status', 'A')->get();
        
        return view('payments.save')->with('payment', $payment)
                                    ->with('contract', $contract)
                                    ->with('invoices', $invoices)
                                    ->with('age_discount', $age_discount)
                                    ->with('other_discounts', $other_discounts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function future($contract_id)
    {
        $payment = new Payment();
        $contract = Contract::find(Crypt::decrypt($contract_id));
        $last_invoice_canceled = $contract->invoices()->where('status', 'C')
                                                    ->orderBy('year', 'DESC')
                                                    ->orderBy('month', 'DESC')->first();
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
                                    ->where('status', 'A')->get();
        
        return view('payments.future')->with('payment', $payment)
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
        $str_description = "";
        $tot_invoices =0;
        $tot_discount=0;
        $total =0;
        //1. Se registra el pago
        $payment = new Payment();
        $payment->date= (new ToolController)->format_ymd($request->input('date'));
        $contract = Contract::find($request->input('hdd_contract_id'));
        $payment->citizen_id = $contract->citizen->id;
        $payment->contract_id = $contract->id;
        $payment->type= $request->input('type');
        $payment->observation = $request->input('observation');
        $payment->amount= 0;
        $payment->save();
        //2. Se actualiza el status de los recibos seleccionados.        
        foreach ($request->input('invoices') as $invoice_id) {
            $invoice = Invoice::find($invoice_id);
            $invoice->payment_id = $payment->id;
            $invoice->status = 'C';
            $tot_invoices = $tot_invoices + $invoice->total;
            $str_description = $str_description.' '.$invoice->month.'/'.$invoice->year;            
            $invoice->save();
        }        
        //3. Se calcula monto del descuento
        $discount = Discount::find($request->input('hdd_discount_id'));
        if($discount){
            if($discount->type == 'M'){
                $tot_discount = $discount->amount;
            }else if($discount->type == 'P'){
                $tot_discount = $tot_invoices * ($discount->percent/100);
            }
            //4. Se registra el movimiento de descuento
            $movement = new Movement();
            $movement->citizen_id = $contract->citizen->id;
            $movement->contract_id = $contract->id;
            $movement->movement_type = 'D';
            $movement->type = 'D';
            $movement->payment_id = $payment->id;
            $movement->date = $payment->date= (new ToolController)->format_ymd($request->input('date'));
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
        //5. Se actualiza el monto del pago
        $str_description = 'Pago Servicio de Agua Meses ('.$str_description.' )';
        $payment->description = $str_description;
        $payment->amount= $tot_invoices-$tot_discount;
        $payment->save();
        //6. Se registra el movimiento del pago
        $movement = new Movement();
        $movement->citizen_id = $contract->citizen->id;
        $movement->contract_id = $contract->id;
        $movement->movement_type = 'D';
        $movement->type = 'P';
        $movement->payment_id = $payment->id;
        $movement->date = $payment->date= (new ToolController)->format_ymd($request->input('date'));
        $movement->amount = $tot_invoices-$tot_discount;
        $movement->description = $str_description;
        $movement->save();
        //Se registra el monto total de los recibos como detalle del pago
        $payment_detail = new PaymentDetail();
        $payment_detail->payment_id = $payment->id;
        $payment_detail->type = 'C';
        $payment_detail->amount = $tot_invoices;
        $payment_detail->description = $str_description;
        $payment_detail->save();
        
        return redirect()->route('payments.contracts_debt')->with('notity', 'create');
    }

    public function payment_future(Request $request){
        
        $str_description = "";
        $tot_invoices =0;
        $tot_discount=0;
        $total =0;
        $contract = Contract::find($request->input('hdd_contract_id'));
        $flat_rate = Rate::find(1);
        $iva = Charge::find(1);
        $initial_month = intval($request->input('hdd_initial_month'));
        $final_month = intval($request->input('final_month'));

        //1. Se registra el pago
        $payment = new Payment();
        $payment->date= (new ToolController)->format_ymd($request->input('date'));
        $payment->citizen_id = $contract->citizen->id;
        $payment->contract_id = $contract->id;
        $payment->type= $request->input('type');
        $payment->observation = $request->input('observation');
        $payment->amount= 0;
        $payment->save();

        //2. Se generan los recibos del periodo selecionado (Incluye cargos e IVA).        
        for ($i= $initial_month; $i <= $final_month ; $i++) { 
            //Paso 2.1 Registrar los datos generales del recibo
            $invoice = new Invoice();
            $invoice->date = (new ToolController)->format_ymd($request->input('date'));
            $invoice->date_limit = (new ToolController)->format_ymd($request->input('date'));
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
            $invoice->save();
            $str_description = $str_description.' '.$invoice->month.'/'.$invoice->year; 
            //Paso 2.2 Registrar el detalle del recibo
            
            //Paso 2.2.1 Incluir Cargo por Tarifa
            $invoice_detail = new InvoiceDetail();
            $invoice_detail->invoice_id = $invoice->id;              
            $invoice_detail->movement_type = 'CT';
            $invoice_detail->type = 'M';             
            $invoice_detail->description = 'Servicio de Agua';            
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
            //Se calcula el monto final del recibo
            $invoice->total = $invoice->total_calculated();
            $invoice->save();
            $tot_invoices = $tot_invoices + $invoice->total;
            //3. Registrar el cargo en la tabla movimientos
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
            $movement->date = $payment->date= (new ToolController)->format_ymd($request->input('date'));
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
        $str_description = 'Pago Servicio de Agua Meses ('.$str_description.' )';
        $payment->amount = $tot_invoices - $tot_discount;
        $payment->description = $str_description;
        $payment->save();

        //6. Se registra el movimiento del pago
        $movement = new Movement();
        $movement->citizen_id = $contract->citizen->id;
        $movement->contract_id = $contract->id;
        $movement->movement_type = 'D';
        $movement->type = 'P';
        $movement->payment_id = $payment->id;
        $movement->date = $payment->date= (new ToolController)->format_ymd($request->input('date'));
        $movement->amount = $tot_invoices-$tot_discount;
        $movement->description = $str_description;
        $movement->save();
        //Se registra el monto total de los recibos como detalle del pago
        $payment_detail = new PaymentDetail();
        $payment_detail->payment_id = $payment->id;
        $payment_detail->type = 'C';
        $payment_detail->amount = $tot_invoices;
        $payment_detail->description = $str_description;
        $payment_detail->save();
    
        return redirect()->route('payments.contracts_solvent')->with('notity', 'create');
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payment = Payment::find(Crypt::decrypt($id));
        return view('payments.save')->with('payment', $payment);
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
        $payment->name= $request->input('name');
        $payment->save();
        return redirect()->route('payments.index')->with('notity', 'update');
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
        //3. Se cambia el estatus de los recibos cancelados (status P=Pendiente y su payment_id=NULL)
        $payment->invoices()->update(array('status' => 'P', 'payment_id' => null));
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
}
