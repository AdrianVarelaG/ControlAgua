<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Contract\ContractRequestStore;
use App\Http\Requests\Contract\ContractRequestUpdate;
use App\Http\Requests\Contract\ContractRequestActivate;
use App\Models\Contract;
use App\Models\Citizen;
use App\Models\Company;
use App\Models\Rate;
use App\Models\State;
use App\Models\Movement;
use App\Models\Payment;
use App\Models\Administration;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Auth;
use Session;
use PDF;


class ContractController extends Controller
{
        
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Company::first();                  
        $current_year = Carbon::now()->year;

        if(Session::get('filter_name')==''){
            $contracts = Contract::paginate(10);
        }else{
            $contracts = Contract::whereHas('Citizen', 
                                function($q){
                                    $q->where('name', 'LIKE', '%'.Session::get('filter_name').'%');
                                })->paginate(10);                
        }
        
        return view('contracts.index')->with('contracts', $contracts)
                                    ->with('current_year', $current_year)
                                    ->with('company', $company);  
    }
    
    public function filter($name){
        
        Session::put('filter_name', $name);
        return redirect()->route('contracts.index');
    }
    
    public function initial_balance_filter($name){
        
        Session::put('filter_name', $name);
        return redirect()->route('contracts.initial_balance');
    }

    public function invoices($contract_id){

        $company = Company::first();        
        $contract = Contract::find(Crypt::decrypt($contract_id));        
        $invoices = $contract->invoices()->where('total', '>', 0)
                                        ->orderBy('date', 'DESC')->paginate(10);
            
        return view('contracts.invoices')->with('contract', $contract)
                                        ->with('invoices', $invoices)
                                        ->with('company', $company);            
    }
    
    public function payments($contract_id){

        $company = Company::first();        
        $contract = Contract::find(Crypt::decrypt($contract_id));        
        $payments = $contract->payments()->orderBy('date', 'DESC')->paginate(10);

        return view('contracts.payments')->with('contract', $contract)
                                        ->with('payments', $payments)
                                        ->with('company', $company);
    }
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function initial_balance()
    {
        $company = Company::first();                  
        $contracts = Contract::where('status', 'D')->paginate(10);  
        
        if(Session::get('filter_name')==''){
            $contracts = Contract::where('status', 'D')->paginate(10); 
        }else{
            $contracts = Contract::where('status', 'D')
                                ->whereHas('Citizen', 
                                    function($q){
                                        $q->where('name', 'LIKE', '%'.Session::get('filter_name').'%');
                                    })->paginate(10);                
        }
        
        return view('contracts.initial_balance')->with('company', $company)
                                            ->with('contracts', $contracts); 
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function activate($id)
    {
        $contract = Contract::find(Crypt::decrypt($id));
        return view('contracts.save_activate')->with('contract', $contract);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_activate(ContractRequestActivate $request, $id)
    {
        $contract = Contract::find($id);
        //1. Se registra en la tabla movimientos el saldo inicial        
        $movement = new Movement();
        $movement->date= (new ToolController)->format_ymd($request->input('date'));
        $movement->contract_id = $contract->id;
        $movement->citizen_id = $contract->citizen->id;
        $movement->description = 'Saldo Inicial';
        $movement->type = 'I';
        $initial_balance = $request->input('initial_balance');
        if ($initial_balance >= 0){
            $movement->movement_type = 'D'; //Debito (saldo a favor del ciudadano)
        }else if($initial_balance < 0 ){            
            $movement->movement_type = 'C'; //Credito (deuda o cargo)
        }
        $movement->amount= abs($request->input('initial_balance'));
        $movement->save();
        //2. Se registra un pago inicial para guardar la fecha del ultimo pago
        $payment = new Payment();
        $payment->date= (new ToolController)->format_ymd($request->input('date_last_payment'));
        $payment->citizen_id = $contract->citizen->id;
        $payment->contract_id = $contract->id;
        $payment->type= 'E';
        $payment->observation = 'Ultimo Pago antes de arranque del Sistema';
        $payment->amount =0;
        $payment->save();
        return redirect()->route('contracts.initial_balance', Crypt::encrypt($contract->citizen_id))->with('notity', 'update');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function citizen_contracts($citizen_id)
    {
        $company = Company::first();                  
        $citizen = Citizen::find(Crypt::decrypt($citizen_id));
        $contracts = $citizen->contracts()->get();  
        return view('contracts.citizen_contracts')->with('company', $company)
                                            ->with('citizen', $citizen)
                                            ->with('contracts', $contracts); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($citizen_id)
    {
        $contract = new Contract();
        $citizen = Citizen::find(Crypt::decrypt($citizen_id));        
        $rates = Rate::where('status', 'A')->orderBy('name')->lists('name','id');
        $administrations = Administration::where('status', 'A')->orderBy('period')->lists('period','id');
        $states = State::where('status', 'A')->orderBy('name')->lists('name','id');
        return view('contracts.save')->with('contract', $contract)
                                    ->with('citizen', $citizen)
                                    ->with('rates', $rates)
                                    ->with('administrations', $administrations)
                                    ->with('states', $states);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContractRequestStore $request)
    {
        $contract = new Contract();
        $contract->citizen_id= $request->input('hdd_citizen_id');
        $contract->number= $request->input('number');
        $contract->date= (new ToolController)->format_ymd($request->input('date'));        
        $contract->rate_id= $request->input('rate'); 
        $contract->administration_id= $request->input('administration');        
        $contract->state_id= $request->input('state');
        $contract->municipality_id= $request->input('municipality');
        $contract->street= $request->input('street');
        $contract->neighborhood= $request->input('neighborhood');
        $contract->number_ext= $request->input('number_ext');
        $contract->number_int= $request->input('number_int');
        $contract->postal_code= $request->input('postal_code');
        $contract->observation= $request->input('observation');
        $contract->status= $request->input('status');
        $contract->save();
        return redirect()->route('contracts.citizen_contracts', Crypt::encrypt($contract->citizen_id))->with('notity', 'create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function balance($id, $period)
    {
        $company = Company::first();                  
        $contract = Contract::find(Crypt::decrypt($id));
        $initial_balance=0;
        $movements = $contract->movements;
        $credits =0;
        $debits =0;
        switch ($period) 
        {
            case '3':
                $period_title = 'Estado de Cuenta últimos 3 meses';
                break;
            case '6':
                $period_title = 'Estado de Cuenta últimos 6 meses';
                break;
            case '12':
                $period_title = 'Estado de Cuenta últimos 12 meses';
                break;
            case 'all':
                $period_title = 'Estado de Cuenta Completo';
                break;
        }        
        if($period == '3'){
            $initial_date = Carbon::now()->subMonths(2)->startOfMonth();
            $credits = $contract->credits()->where('date', '<' , $initial_date)->sum('amount');
            $debits = $contract->debits()->where('date', '<' , $initial_date)->sum('amount');
            $initial_balance = $credits - $debits;
            $movements = $contract->movements()->where('date','>=',$initial_date)
                                    ->orderBy('date')->get();
        }        
        else if($period == '6'){
            $initial_date = Carbon::now()->subMonths(5)->startOfMonth();
            $credits = $contract->credits()->where('date', '<' , $initial_date)->sum('amount');
            $debits = $contract->debits()->where('date', '<' , $initial_date)->sum('amount');
            $initial_balance = $credits - $debits;            
            $movements = $contract->movements()->where('date','>=',$initial_date)
                                    ->orderBy('date')->get();

        }
        else if($period == '12'){
            $initial_date = Carbon::now()->subMonths(11)->startOfMonth();
            $credits = $contract->credits()->where('date', '<' , $initial_date)->sum('amount');
            $debits = $contract->debits()->where('date', '<' , $initial_date)->sum('amount');
            $initial_balance = $credits - $debits;            
            $movements = $contract->movements()->where('date','>=',$initial_date)
                                    ->orderBy('date')->get();

        }
        else if($period == 'all'){
            $initial_date = Carbon::now();
            $movements = $contract->movements()->orderBy('date')->get();
        }
                        
        return view('contracts.balance')->with('company', $company)
                                    ->with('contract', $contract)
                                    ->with('initial_balance', $initial_balance)
                                    ->with('initial_date', $initial_date)
                                    ->with('movements', $movements)
                                    ->with('period', $period)
                                    ->with('period_title', $period_title);

    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contract = Contract::find(Crypt::decrypt($id));
        $citizen = Citizen::find($contract->citizen_id);        
        $rates = Rate::where('status', 'A')->orderBy('name')->lists('name','id');
        $administrations = Administration::where('status', 'A')->orderBy('period')->lists('period','id');
        $states = State::where('status', 'A')->orderBy('name')->lists('name','id');
        return view('contracts.save')->with('contract', $contract)
                                    ->with('citizen', $citizen)
                                    ->with('rates', $rates)
                                    ->with('administrations', $administrations)
                                    ->with('states', $states);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ContractRequestUpdate $request, $id)
    {
        $contract = Contract::find($id);
        $contract->number= $request->input('number');
        $contract->date= (new ToolController)->format_ymd($request->input('date'));        
        $contract->rate_id= $request->input('rate'); 
        $contract->administration_id= $request->input('administration');        
        $contract->state_id= $request->input('state');
        $contract->municipality_id= $request->input('municipality');
        $contract->street= $request->input('street');
        $contract->neighborhood= $request->input('neighborhood');
        $contract->number_ext= $request->input('number_ext');
        $contract->number_int= $request->input('number_int');
        $contract->postal_code= $request->input('postal_code');
        $contract->observation= $request->input('observation');
        $contract->status= $request->input('status');
        $contract->save();
        return redirect()->route('contracts.citizen_contracts', Crypt::encrypt($contract->citizen_id))->with('notity', 'update');
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
        * Logica de eliminacion para no generar inconsistencia.
        * Verifica si hay recibos y lecturas asociadas
        */        
        $contract = Contract::find($id);
        if ($contract->invoices->count() == 0){            
            if($contract->readings->count()==0){
                $contract->delete();
                return redirect()->route('contracts.citizen_contracts', Crypt::encrypt($contract->citizen_id))->with('notity', 'delete');
            }else{
                return redirect()->route('contracts.citizen_contracts', Crypt::encrypt($contract->citizen_id))->withErrors('No se puede eliminar el Contrato. Existen <strong>'.$contract->readings->count().'</strong> lecturas asociadas. Debe primero eliminar las lecturas asociadas. Gracias...');            
            }
        
        }else{            
            return redirect()->route('contracts.citizen_contracts', Crypt::encrypt($contract->citizen_id))->withErrors('No se puede eliminar el Contrato. Existen <strong>'.$contract->invoices->count().'</strong> recibos asociados. Debe primero eliminar los recibos asociados. Gracias...');            
        }
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
        $contract = Contract::find(Crypt::decrypt($id));
        if($contract->status == "A"){
            if($contract->balance > 0){
                return redirect()->route('contracts.index')->withErrors('Para deshabilitar un contrato no debe tener deuda pendiente.');                 
            }else{
                $contract->status="D";                
            }        
        }else{
            $contract->status= "A"; 
        }
        $contract->save();
        return redirect()->route('contracts.index');
    }

    public function rpt_contracts($filter)
    {

        $company = Company::first();
        
        $contracts = Contract::whereHas('Citizen', 
                                function($q) use ($filter){
                                    $q->where('name', 'LIKE', '%'.$filter.'%');
                                })->orderBy('number')->get();
        $data=[
            'company' => $company,
            'contracts' => $contracts,
            'logo' => 'data:image/png;base64, '.$company->logo 
        ];
        $pdf = PDF::loadView('reports/rpt_contracts', $data);
        
        return $pdf->download('Ciudadanos.pdf');

    }

    public function rpt_contract_invoices($id)
    {            
            
        $company = Company::first();

        $contract = Contract::find($id);
        $invoices = $contract->invoices()->where('total', '>', 0)
                                        ->orderBy('date', 'DESC')->get();        
        $data=[
            'company' => $company,
            'contract' => $contract,
            'invoices' => $invoices,
            'logo' => 'data:image/png;base64, '.$company->logo 
        ];
        $pdf = PDF::loadView('reports/rpt_contract_invoices', $data);
        
        return $pdf->download('Recibos del Contrato '.$contract->id.'.pdf');
    }

    public function rpt_contract_payments($id)
    {            
            
        $company = Company::first();

        $contract = Contract::find($id);
        $payments = $contract->payments()->orderBy('date', 'DESC')->get();        
        
        $data=[
            'company' => $company,
            'contract' => $contract,
            'payments' => $payments,
            'logo' => 'data:image/png;base64, '.$company->logo 
        ];
        $pdf = PDF::loadView('reports/rpt_contract_payments', $data);
        
        return $pdf->download('Pagos del Contrato '.$contract->id.'.pdf');
    }

}
