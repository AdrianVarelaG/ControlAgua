<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Company\CompanyRequestStore;
use App\Http\Requests\Company\CompanyRequestUpdate;
use App\Models\Company;
use App\Models\Country;
use Illuminate\Support\Facades\Crypt;
//Image
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\ImgController;
use Image;
use File;



class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Company::all();
        
        if($company->count()){
            $company = Company::first();
        }else{
            $company = new Company();
        }
        
        return view('company.save')->with('company', $company);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company = new Company();
        return view('company.save')->with('company', $company);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequestStore $request)
    {
        $company = new Company();        
        // Codigo para el logo
        $file = Input::file('logo');        
        if (!File::exists($file))
        {        
            //Si el archivo no existe toma el avatar por defecto
            $file = public_path()."/img/avatar_default.png";
        }
        $img = Image::make($file)->encode('jpg');
        $company->logo = base64_encode((new ImgController)->resize_image($img, 'jpg', 200, 200)); 
        $company->name= $request->input('name');
        $company->ID_company= $request->input('ID_company');
        $company->address= $request->input('address');
        $company->company_phone= $request->input('company_phone');
        $company->company_email= $request->input('company_email');
        $company->contact= $request->input('contact');
        $company->contact_phone= $request->input('contact_phone');
        $company->contact_email= $request->input('contact_email');
        $company->save();
        return view('home');
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
        $company = Company::find(Crypt::decrypt($id));
        return view('company.save')->with('company', $company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyRequestUpdate $request, $id)
    {
        $company = Company::find($id);        
        // Codigo para el logo
        $file = Input::file('logo');
        if (File::exists($file))
        {        
            $img = Image::make($file)->encode('jpg');
            $company->logo = base64_encode((new ImgController)->resize_image($img, 'jpg', 200, 200)); 
        }
        $company->name= $request->input('name');
        $company->ID_company= $request->input('ID_company');
        $company->address= $request->input('address');
        $company->company_phone= $request->input('company_phone');
        $company->company_email= $request->input('company_email');
        $company->contact= $request->input('contact');
        $company->contact_phone= $request->input('contact_phone');
        $company->contact_email= $request->input('contact_email');
        $company->save();
        return view('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
