<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Municipality\MunicipalityRequestStore;
use App\Http\Requests\Municipality\MunicipalityRequestUpdate;
use App\Models\Municipality;
use App\Models\State;
use App\Models\Company;
use Illuminate\Support\Facades\Crypt;
use Session;


class MunicipalityController extends Controller
{
    
    // Metodo que retorna los estados de un país para combos anidados
    public function getMunicipalities(Request $request, $id){

        if ($request->ajax()){
            $municipalities = Municipality::states($id);
            return response()->json($municipalities);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Company::first();
        $state = State::find(Session::get('state_id'));
        $states = State::where('status', 'A')->orderBy('name')->lists('name','id');
        return view('municipalities.index')->with('states', $states)
                                            ->with('state', $state)
                                            ->with('company', $company);  
    }

    public function change_state($state_id){
        
        Session::put('state_id', $state_id);
        return redirect()->route('municipalities.index');
    }    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $municipality = new Municipality();
        $states = State::where('status', 'A')->orderBy('name')->lists('name','id');
        return view('municipalities.save')->with('municipality', $municipality)
                                  ->with('states', $states);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MunicipalityRequestStore $request)
    {
        $municipality = new Municipality();
        $municipality->state_id= $request->input('state');
        $municipality->name= $request->input('name');
        $municipality->status= 'A';
        $municipality->save();
        return redirect()->route('municipalities.index')->with('notity', 'create');
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
        $municipality = Municipality::find(Crypt::decrypt($id));
        $states = State::where('status', 'A')->orderBy('name')->lists('name','id');
        return view('municipalities.save')->with('municipality', $municipality)
                                  ->with('states', $states);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MunicipalityRequestUpdate $request, $id)
    {
        $municipality = Municipality::find($id);        
        $municipality->state_id= $request->input('state');
        $municipality->name= $request->input('name');
        $municipality->save();
        return redirect()->route('municipalities.index')->with('notity', 'update');
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
        $municipality = Municipality::find($id);
        if ($municipality->citizens->count() == 0){            
            $municipality->delete();
            return redirect()->route('municipalities.index')->with('notity', 'delete');       
        }else{            
            return redirect()->route('municipalities.index')->withErrors('No se puede eliminar el Municipio. Existen <strong>'.$municipality->citizens->count().'</strong> ciudadanos asociados. Debe primero eliminar los ciudadanos asociados. Gracias...');            
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
        $municipality = Municipality::find(Crypt::decrypt($id));
        ($municipality->status == "A")?$municipality->status="D":$municipality->status= "A";  
        $municipality->save();
        return redirect()->route('municipalities.index');
    }

}
