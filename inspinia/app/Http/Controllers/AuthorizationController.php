<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Authorization;
use App\Http\Requests\Authorization\AuthorizationRequestStore;
use App\Http\Requests\Authorization\AuthorizationRequestUpdate;
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

class AuthorizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authorizations = Authorization::all();  
        $company = Company::first();
        return view('authorizations.index')->with('authorizations', $authorizations)
                                    ->with('company', $company); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $authorization = new Authorization();
        return view('authorizations.save')->with('authorization', $authorization);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuthorizationRequestStore $request)
    {
        $authorization = new Authorization();        
        $file = Input::file('avatar');        
        if (!File::exists($file))
        {        
            $file = public_path()."/img/avatar_default.png";
        }
        $img = Image::make($file)->encode('jpg');
        $authorization->avatar = base64_encode((new ImgController)->resize_image($img, 'jpg', 200, 200));
        $authorization->name= $request->input('name');
        $authorization->position= $request->input('position');        
        $authorization->email= $request->input('email');
        $authorization->status= 'A';
        $authorization->save();
        return redirect()->route('authorizations.index')->with('notity', 'create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $authorization = Authorization::find(Crypt::decrypt($id));        
        return view('authorizations.show')->with('authorization', $authorization);  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $authorization = Authorization::find(Crypt::decrypt($id));        
        return view('authorizations.save')->with('authorization', $authorization);  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AuthorizationRequestUpdate $request, $id)
    {
        $authorization = Authorization::find($id);        
        // Codigo para el logo
        $file = Input::file('avatar');        
        if (File::exists($file))
        {        
            $img = Image::make($file)->encode('jpeg');
            $authorization->avatar = base64_encode((new ImgController)->resize_image($img, 'jpg', 200, 200)); 
        }
        $authorization->name= $request->input('name');
        $authorization->position= $request->input('position');        
        $authorization->email= $request->input('email');
        $authorization->save();
        return redirect()->route('authorizations.index')->with('notity', 'update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $authorization = Authorization::find($id);
        if ($authorization->discounts->count() == 0){            
            $authorization->delete();
            return redirect()->route('authorizations.index')->with('notity', 'delete');        
        }else{            
            return redirect()->route('authorizations.index')->withErrors('No se puede eliminar la persona que autoriza. Existen <strong>'.$authorization->discounts->count().'</strong> descuentos asociados.<br>Debe primero eliminar los descuentos asociados.<br> Tambien puede deshabilitar la persona para que no siga autorizando descuentos.');  
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
        $authorization = Authorization::find(Crypt::decrypt($id));
        ($authorization->status == "A")?$authorization->status="D":$authorization->status= "A";  
        $authorization->save();
        return redirect()->route('authorizations.index');
    }

}
