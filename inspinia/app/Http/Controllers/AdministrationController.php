<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Administration\AdministrationRequestStore;
use App\Http\Requests\Administration\AdministrationRequestUpdate;
use App\Models\Administration;
use App\Models\Company;
use Illuminate\Support\Facades\Crypt;


class AdministrationController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $administrations = Administration::all();
        $company = Company::first();          
        return view('administrations.index')->with('administrations', $administrations)
                                    ->with('company', $company); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $administration = new Administration();
        return view('administrations.save')->with('administration', $administration);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdministrationRequestStore $request)
    {
        $administration = new Administration();
        $administration->period= $request->input('period');
        $administration->authority= $request->input('authority');
        $administration->position= $request->input('position');
        $administration->status= 'A';
        $administration->save();
        return redirect()->route('administrations.index')->with('notity', 'create');
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
        $administration = Administration::find(Crypt::decrypt($id));
        return view('administrations.save')->with('administration', $administration);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdministrationRequestUpdate $request, $id)
    {
        $administration = Administration::find($id);        
        $administration->period= $request->input('period');
        $administration->authority= $request->input('authority');
        $administration->position= $request->input('position');
        $administration->save();
        return redirect()->route('administrations.index')->with('notity', 'update');
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
        $administration = Administration::find($id);
        if ($administration->contracts->count() == 0){            
            $administration->delete();
            return redirect()->route('administrations.index')->with('notity', 'delete');        
        }else{            
            return redirect()->route('administrations.index')->withErrors('No se puede eliminar la Administración. Existen <strong>'.$administration->contracts->count().'</strong> contratos asociados. Debe primero eliminar los contractos asociados. Gracias...');            
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
        $administration = Administration::find(Crypt::decrypt($id));
        ($administration->status == "A")?$administration->status="D":$administration->status= "A";  
        $administration->save();
        return redirect()->route('administrations.index');
    }
}
