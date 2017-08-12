<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\State\StateRequestStore;
use App\Http\Requests\State\StateRequestUpdate;
use App\Models\State;
use App\Models\Company;
use Illuminate\Support\Facades\Crypt;


class StateController extends Controller
{
    
    // Metodo que retorna los estados de un país para combos anidados
    public function getStates(Request $request, $id){

        if ($request->ajax()){
            $states = State::states($id);
            return response()->json($states);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $states = State::all();
        $company = Company::first();          
        return view('states.index')->with('states', $states)
                                    ->with('company', $company); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $state = new State();
        return view('states.save')->with('state', $state);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StateRequestStore $request)
    {
        $state = new State();
        $state->name= $request->input('name');
        $state->status= 'A';
        $state->save();
        return redirect()->route('states.index')->with('notity', 'create');
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
        $state = State::find(Crypt::decrypt($id));
        return view('states.save')->with('state', $state);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StateRequestUpdate $request, $id)
    {
        $state = State::find($id);        
        $state->name= $request->input('name');
        $state->save();
        return redirect()->route('states.index')->with('notity', 'update');
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
        $state = State::find($id);
        if ($state->municipalities->count() == 0){            
            $state->delete();
            return redirect()->route('states.index')->with('notity', 'delete');        
        }else{            
            return redirect()->route('states.index')->withErrors('No se puede eliminar el Estado. Existen <strong>'.$state->municipalities->count().'</strong> Municipios asociados. Debe primero eliminar los Municipios asociados. Gracias...');            
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
        $state = State::find(Crypt::decrypt($id));
        ($state->status == "A")?$state->status="D":$state->status= "A";  
        $state->save();
        return redirect()->route('states.index');
    }
}
