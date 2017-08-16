<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Rate\RateRequestStore;
use App\Http\Requests\Rate\RateRequestUpdate;
use App\Models\Rate;
use App\Models\Company;
use Illuminate\Support\Facades\Crypt;
use Auth;


class RateController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Company::first();                  
        $rates = Rate::where('id','>', 1)->get();  
        return view('rates.index')->with('rates', $rates)
                                            ->with('company', $company);  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rate = new Rate();
        return view('rates.save')->with('rate', $rate);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RateRequestStore $request)
    {
        $rate = new Rate();
        $rate->name= $request->input('name');
        $rate->amount= $request->input('amount');
        $rate->observation= $request->input('observation');
        $rate->status= 'A';
        $rate->created_by= Auth::user()->name;
        $rate->save();
        return redirect()->route('rates.index')->with('notity', 'create');
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
    public function flat_rate()
    {
        $rate = Rate::find(1);
        return view('rates.save')->with('rate', $rate);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rate = Rate::find(Crypt::decrypt($id));
        return view('rates.save')->with('rate', $rate);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RateRequestUpdate $request, $id)
    {
        $rate = Rate::find($id);        
        $rate->name= $request->input('name');
        $rate->movement_type= 'CT';
        $rate->amount= $request->input('amount');
        $rate->observation= $request->input('observation');
        $rate->created_by= Auth::user()->name;
        $rate->status= 'A';
        $rate->save();
        if($rate->id == 1){
            return view('home');
        }else{
            return redirect()->route('rates.index')->with('notity', 'update');
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
        $rate = Rate::find($id);
        if ($rate->contracts->count() == 0){            
            $rate->delete();
            return redirect()->route('rates.index')->with('notity', 'delete');        
        }else{            
            return redirect()->route('rates.index')->withErrors('No se puede eliminar la tarifa. Existen <strong>'.$rate->contracts->count().'</strong> contratos asociados.<br>Debe primero eliminar los contratos asociados.<br>También puede deshabilitar la tarifa para que no siga siendo utilizada en el sistema.');            
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
        $rate = Rate::find(Crypt::decrypt($id));
        ($rate->status == "A")?$rate->status="D":$rate->status= "A";  
        $rate->save();
        return redirect()->route('rates.index');
    }

}
