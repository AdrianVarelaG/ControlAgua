<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Charge\ChargeRequestStore;
use App\Http\Requests\Charge\ChargeRequestUpdate;
use App\Models\Charge;
use App\Models\Company;
use Illuminate\Support\Facades\Crypt;


class ChargeController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $charges = Charge::where('id','>', 1)->get();
        $company = Company::first();          
        return view('charges.index')->with('charges', $charges)
                                    ->with('company', $company); 
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $charge = new Charge();
        return view('charges.save')->with('charge', $charge);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChargeRequestStore $request)
    {        
        $charge = new Charge();
        $charge->movement_type= 'CA';
        $charge->type= $request->input('type');
        if ($request->input('type')=='M'){
            $charge->amount= $request->input('amount');
        }elseif($request->input('type')=='P'){
            $charge->percent= $request->input('percent');
        }        
        $charge->description= $request->input('description');
        $charge->status= 'A';
        $charge->save();
        return redirect()->route('charges.index')->with('notity', 'create');
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function iva()
    {
        $charge = Charge::find(1);
        return view('charges.save')->with('charge', $charge);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $charge = Charge::find(Crypt::decrypt($id));
        return view('charges.save')->with('charge', $charge);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ChargeRequestUpdate $request, $id)
    {
        $charge = Charge::find($id);
        if($charge->id == 1){
            $charge->percent= $request->input('percent');
        }else{
            $charge->type= $request->input('type');
            if ($request->input('type')=='M'){
                $charge->amount= $request->input('amount');
                $charge->percent= 0;
            }elseif($request->input('type')=='P'){
                $charge->percent= $request->input('percent');
                $charge->amount= 0;
            }        
        }
        $charge->description= $request->input('description');
        $charge->save();
        
        if($charge->id == 1){
            return redirect()->route('home')->with('notity', 'update');
            //return view('home');
        }else{
            return redirect()->route('charges.index')->with('notity', 'update');
        }
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
        */        
        $charge = Charge::find($id);
        $charge->delete();
        
        return redirect()->route('charges.index')->with('notity', 'delete');        
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
        $charge = Charge::find(Crypt::decrypt($id));
        ($charge->status == "A")?$charge->status="D":$charge->status= "A";  
        $charge->save();
        return redirect()->route('charges.index');
    }
}
