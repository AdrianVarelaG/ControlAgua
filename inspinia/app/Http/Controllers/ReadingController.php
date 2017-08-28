<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Reading\ReadingRequestStore;
use App\Http\Requests\Reading\ReadingRequestUpdate;
use App\Models\Reading;
use App\Models\Company;
use App\Models\Inspector;
use App\Models\Contract;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;


class ReadingController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $readings = Reading::orderBy('date')->orderBy('contract_id')->get();
        $company = Company::first();          
        return view('readings.index')->with('readings', $readings)
                                    ->with('company', $company); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $reading = new Reading();
        //Seteo de los ultimos valores
        $readings = Reading::all();
        if($readings->count()){
            $last_reading = Reading::all()->last();
            $last_month = (strlen($last_reading->month)==1?'0'.$last_reading->month:$last_reading->month);
            $last_year = $last_reading->year;
            $last_date = $last_reading->date;
            $last_inspector_id = $last_reading->inspector_id;
        }else{
            
            $last_month = (strlen(Carbon::now()->month)==1?'0'.Carbon::now()->month:Carbon::now()->month);
            $last_year = Carbon::now()->year;
            $last_date = Carbon::now();
            $last_inspector_id = "";
        }        
        $inspectors = Inspector::where('status', 'A')->orderBy('name')->lists('name','id');        
        $contracts = Contract::where('status', 'A')->orderBy('number')->lists('number','id');        
        
        return view('readings.save')->with('reading', $reading)
                                    ->with('contracts', $contracts)
                                    ->with('inspectors', $inspectors)
                                    ->with('last_month', $last_month)
                                    ->with('last_year', $last_year)
                                    ->with('last_date', $last_date)
                                    ->with('last_inspector_id', $last_inspector_id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReadingRequestStore $request)
    {
        $contract_id = $request->input('contract');            
        $month = substr($request->input('period'),0,2);
        $year = substr($request->input('period'),3,4);        
        
        if(!$this->reading_exist($contract_id, $year, $month)){
            $reading = new Reading();
            $reading->date = (new ToolController)->format_ymd($request->input('date'));       
            $reading->contract_id = $contract_id;            
            $reading->month = $month;
            $reading->year = $year;                    
            $reading->inspector_id = $request->input('inspector');
            $reading->previous_reading = $request->input('previous_reading');
            $reading->current_reading = $request->input('current_reading');
            $reading->consume = $reading->current_reading - $reading->previous_reading;
            $reading->observation = $request->input('observation');        
            $reading->save();
            return redirect()->route('readings.index')->with('notity', 'create');
        }else{
            $contract = Contract::find($contract_id);
            return redirect()->route('readings.create')->withErrors('Ya existe una lectura para el contrato <strong>'.$contract->number.'</strong> en el período <strong>'.$month.'/'.$year.'</strong>.');
        }
    }

    
    public function reading_exist($contract_id, $year, $month){
        $reading = Reading::where('contract_id', $contract_id)
                            ->where('year', $year)
                            ->where('month', $month);
        if($reading->count()>0){
            return true;
        }else{
            return false;
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $reading = Reading::find(Crypt::decrypt($id));
        $inspectors = Inspector::where('status', 'A')->orderBy('name')->lists('name','id');        
        $contracts = Contract::where('status', 'A')->orderBy('number')->lists('number','id');        
        
        return view('readings.save')->with('reading', $reading)
                                    ->with('contracts', $contracts)
                                    ->with('inspectors', $inspectors);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ReadingRequestUpdate $request, $id)
    {
        $reading = Reading::find($id);        
        $reading->month = substr($request->input('period'),0,2);
        $reading->year = substr($request->input('period'),3,4);        
        $reading->date = (new ToolController)->format_ymd($request->input('date'));       
        $reading->inspector_id = $request->input('inspector');
        $reading->contract_id = $request->input('contract');
        $reading->previous_reading = $request->input('previous_reading');
        $reading->current_reading = $request->input('current_reading');
        $reading->consume = $reading->current_reading - $reading->previous_reading;
        $reading->observation = $request->input('observation');        
        $reading->save();
        return redirect()->route('readings.index')->with('notity', 'update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reading = Reading::find($id);
        if ($reading->invoice){            
            return redirect()->route('readings.index')->withErrors('No puede eliminar la lectura. El recibo # <strong>'.$reading->invoice->id.'</strong> ha sido generado con esa lectura. Debe primero eliminar el recibo.');
        }else{            
            $reading->delete();
            return redirect()->route('readings.index')->with('notity', 'delete');        
        }
    }

    // Metodo que retorna la ultima lectura de un Contrato
    public function getLastReading(Request $request, $contract_id){

        if ($request->ajax()){
            $contract = Contract::find($contract_id);
            $last_reading = $contract->readings()->orderBy('date', 'DESC')->first();
            if($last_reading){
                return response()->json($last_reading);
            }else{
                return response()->json(0);
            }
        }
    }

}
