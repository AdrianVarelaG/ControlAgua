<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Inspector;
use App\Http\Requests\Inspector\InspectorRequestStore;
use App\Http\Requests\Inspector\InspectorRequestUpdate;
use Auth;
use App\Models\Company;
use Illuminate\Support\Facades\Crypt;
//Image
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\ImgController;
use Image;
use File;
use DB;

class InspectorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inspectors = Inspector::all();  
        $company = Company::first();
        return view('inspectors.index')->with('inspectors', $inspectors)
                                    ->with('company', $company); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $inspector = new Inspector();
        return view('inspectors.save')->with('inspector', $inspector);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InspectorRequestStore $request)
    {
        $inspector = new Inspector();        
        $file = Input::file('avatar');        
        if (!File::exists($file))
        {        
            $file = public_path()."/img/avatar_default.png";
        }
        $img = Image::make($file)->encode('jpg');
        $inspector->avatar = base64_encode((new ImgController)->resize_image($img, 'jpg', 200, 200));         
        $inspector->name= $request->input('name');
        $inspector->ID_number= $request->input('ID_number');
        $inspector->email= $request->input('email');
        $inspector->phone= $request->input('phone');
        $inspector->mobile= $request->input('mobile');
        $inspector->status= 'A';
        $inspector->save();
        return redirect()->route('inspectors.index')->with('notity', 'create');
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
        $inspector = Inspector::find(Crypt::decrypt($id));        
        return view('inspectors.save')->with('inspector', $inspector);  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InspectorRequestUpdate $request, $id)
    {
        $inspector = Inspector::find($id);        
        // Codigo para el logo
        $file = Input::file('avatar');        
        if (File::exists($file))
        {        
            $img = Image::make($file)->encode('jpeg');
            $inspector->avatar = base64_encode((new ImgController)->resize_image($img, 'jpg', 200, 200)); 
        }
        $inspector->name= $request->input('name');
        $inspector->ID_number= $request->input('ID_number');
        $inspector->email= $request->input('email');
        $inspector->phone= $request->input('phone');
        $inspector->mobile= $request->input('mobile');
        $inspector->save();
        return redirect()->route('inspectors.index')->with('notity', 'update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Falta validar registros asociados para eliminar
        $inspector = Inspector::find($id);
        $inspector->delete();
        return redirect()->route('inspectors.index')->with('notity', 'delete');
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
        $inspector = Inspector::find(Crypt::decrypt($id));
        ($inspector->status == "A")?$inspector->status="D":$inspector->status= "A";  
        $inspector->save();
        return redirect()->route('inspectors.index');
    }

}
