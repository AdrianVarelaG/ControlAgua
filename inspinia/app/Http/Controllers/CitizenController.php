<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Citizens;
use App\Http\Requests\Citizen\CitizenRequestStore;
use App\Http\Requests\Citizen\CitizenRequestUpdate;
use Auth;
use App\Models\Company;
use App\Models\Citizen;
use App\Models\State;
use Illuminate\Support\Facades\Crypt;
//Image
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\ImgController;
use Image;
use File;
use DB;

class CitizenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $citizens = Citizen::all();  
        $company = Company::first();
        return view('citizens.index')->with('citizens', $citizens)
                                    ->with('company', $company); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $citizen = new Citizen();
        $states = State::orderBy('name')->lists('name','id');
        return view('citizens.save')->with('citizen', $citizen)
                                    ->with('states', $states);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CitizenRequestStore $request)
    {
        $citizen = new Citizen();        
        $file = Input::file('avatar');        
        if (!File::exists($file))
        {        
            $file = public_path()."/img/avatar_default.png";
        }
        $img = Image::make($file)->encode('jpg');
        $citizen->avatar = base64_encode((new ImgController)->resize_image($img, 'jpg', 200, 200));         
        $citizen->ID_number= $request->input('ID_number');
        $citizen->RFC= $request->input('RFC');
        $citizen->state_id= $request->input('state');
        $citizen->municipality_id= $request->input('municipality');
        $citizen->name= $request->input('name');
        $citizen->profession= $request->input('profession');
        $citizen->email= $request->input('email');
        $citizen->phone= $request->input('phone');
        $citizen->mobile= $request->input('mobile');
        $citizen->street= $request->input('street');
        $citizen->neighborhood= $request->input('neighborhood');
        $citizen->number_ext= $request->input('number_ext');
        $citizen->number_int= $request->input('number_int');
        $citizen->postal_code= $request->input('postal_code');
        $citizen->status= 'A';
        $citizen->save();
        return redirect()->route('citizens.index')->with('notity', 'create');
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
        $citizen = Citizen::find(Crypt::decrypt($id));        
        $states = State::orderBy('name')->lists('name','id');        
        return view('citizens.save')->with('citizen', $citizen)
                                    ->with('states', $states);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CitizenRequestUpdate $request, $id)
    {
        $citizen = Citizen::find($id);        
        // Codigo para el logo
        $file = Input::file('avatar');        
        if (File::exists($file))
        {        
            $img = Image::make($file)->encode('jpeg');
            $citizen->avatar = base64_encode((new ImgController)->resize_image($img, 'jpg', 200, 200)); 
        }
        $citizen->ID_number= $request->input('ID_number');
        $citizen->RFC= $request->input('RFC');
        $citizen->state_id= $request->input('state');
        $citizen->municipality_id= $request->input('municipality');
        $citizen->name= $request->input('name');
        $citizen->profession= $request->input('profession');
        $citizen->email= $request->input('email');
        $citizen->phone= $request->input('phone');
        $citizen->mobile= $request->input('mobile');
        $citizen->street= $request->input('street');
        $citizen->neighborhood= $request->input('neighborhood');        
        $citizen->number_ext= $request->input('number_ext');
        $citizen->number_int= $request->input('number_int');
        $citizen->postal_code= $request->input('postal_code');
        $citizen->save();
        return redirect()->route('citizens.index')->with('notity', 'update');
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
        $citizen = Citizen::find($id);
        if ($citizen->meters->count() == 0){            
            $citizen->delete();
            return redirect()->route('citizens.index')->with('notity', 'delete');        
        }else{            
            return redirect()->route('citizens.index')->withErrors('No se puede eliminar el ciudadano. Existen <strong>'.$citizens->meters->count().'</strong> medidores asociados. Debe primero eliminar los medidores asociados. Gracias...');            
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
        $citizen = Citizen::find(Crypt::decrypt($id));
        ($citizen->status == "A")?$citizen->status="D":$citizen->status= "A";  
        $citizen->save();
        return redirect()->route('citizens.index');
    }

}
