<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Setting\SettingRequestStore;
use App\Http\Requests\Setting\SettingRequestUpdate;
use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;
use Session;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setting = Setting::all();
        
        if($setting->count()){
            $setting = Setting::first();
        }else{
            $setting = new Setting();
        }
        
        return view('settings.save')->with('setting', $setting);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $setting = new Setting();
        return view('settings.save')->with('setting', $setting);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SettingRequestStore $request)
    {
        $setting = new Setting();
        $setting->app_name= $request->input('app_name');
        $setting->coin= $request->input('coin');
        $setting->money_format= $request->input('money_format');
        ($request->input('create_notification')=='on')?$setting->create_notification=true:$setting->create_notification=false;
        ($request->input('update_notification')=='on')?$setting->update_notification=true:$setting->update_notification=false;
        ($request->input('delete_notification')=='on')?$setting->delete_notification=true:$setting->delete_notification=false;
        $setting->save();
        //Se actualizan las variables de Sesión
        $this->set_session();
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
        $setting = Setting::find(Crypt::decrypt($id));
        return view('settings.save')->with('setting', $setting);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SettingRequestUpdate $request, $id)
    {
        $setting = Setting::find($id);        
        $setting->app_name= $request->input('app_name');
        $setting->coin= $request->input('coin');
        $setting->money_format= $request->input('money_format');
        ($request->input('create_notification')=='on')?$setting->create_notification=true:$setting->create_notification=false;
        ($request->input('update_notification')=='on')?$setting->update_notification=true:$setting->update_notification=false;
        ($request->input('delete_notification')=='on')?$setting->delete_notification=true:$setting->delete_notification=false;
        $setting->save();        
        //Se actualizan las variables de Sesión
        $this->set_session();
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

    public function set_session(){
        $setting = Setting::first();
        Session::put('app_name', $setting->app_name);
        Session::put('coin', $setting->coin);
        Session::put('money_format', $setting->money_format);
        Session::put('create_notification', $setting->create_notification);
        Session::put('update_notification', $setting->update_notification);
        Session::put('delete_notification', $setting->delete_notification);
    }
}
