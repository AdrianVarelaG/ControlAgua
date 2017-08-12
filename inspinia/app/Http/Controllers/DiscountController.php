<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Discount\DiscountRequestStore;
use App\Http\Requests\Discount\DiscountRequestUpdate;
use App\Models\Discount;
use App\Models\Company;
use Illuminate\Support\Facades\Crypt;


class DiscountController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discounts = Discount::where('id','>', 1)->get();
        //$discounts = Discount::all();
        $company = Company::first();          
        return view('discounts.index')->with('discounts', $discounts)
                                    ->with('company', $company); 
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $discount = new Discount();
        return view('discounts.save')->with('discount', $discount);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DiscountRequestStore $request)
    {        
        $discount = new Discount();
        $discount->movement_type= 'D';
        $discount->type= $request->input('type');
        
        if ($request->input('temporary')){
            $discount->temporary = 'Y';
            $discount->initial_date = (new ToolController)->format_ymd($request->input('initial_date'));
            $discount->final_date = (new ToolController)->format_ymd($request->input('final_date'));
        }else{
            $discount->temporary ='N';
        }
        
        if ($request->input('type')=='M'){
            $discount->amount= $request->input('amount');
        }elseif($request->input('type')=='P'){
            $discount->percent= $request->input('percent');
        }        
        $discount->description= $request->input('description');
        $discount->status= 'A';
        $discount->save();
        
        return redirect()->route('discounts.index')->with('notity', 'create');
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
    public function age()
    {
        $discount = Discount::find(1);
        return view('discounts.save')->with('discount', $discount);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $discount = Discount::find(Crypt::decrypt($id));
        return view('discounts.save')->with('discount', $discount);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DiscountRequestUpdate $request, $id)
    {
        $discount = Discount::find($id);
        if($discount->id == 1){
            $discount->age= $request->input('age');
        }else{
            if ($request->input('temporary')){
                $discount->temporary = 'Y';
                $discount->initial_date = (new ToolController)->format_ymd($request->input('initial_date'));
                $discount->final_date = (new ToolController)->format_ymd($request->input('final_date'));
            }else{
                $discount->temporary = 'N';
                $discount->initial_date = null;
                $discount->final_date = null;
            }
        }
        $discount->type= $request->input('type');
        if ($request->input('type')=='M'){
            $discount->amount= $request->input('amount');
        }elseif($request->input('type')=='P'){
            $discount->percent= $request->input('percent');
        }                        
        $discount->description= $request->input('description');
        $discount->status= 'A';
        $discount->save();
        
        if($discount->id == 1){
            return view('home');
        }else{
            return redirect()->route('discounts.index')->with('notity', 'update');
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
        * Se chequea si hay condominios asociados con el pais
        */        
        $discount = Discount::find($id);
        $discount->delete();
        
        return redirect()->route('discounts.index')->with('notity', 'delete');        
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
        $discount = Discount::find(Crypt::decrypt($id));
        ($discount->status == "A")?$discount->status="D":$discount->status= "A";  
        $discount->save();
        return redirect()->route('discounts.index');
    }
}
