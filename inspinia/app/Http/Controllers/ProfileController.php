<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Profile\ProfileRequestUpdate;
use App\User;
use Illuminate\Support\Facades\Crypt;
//Image
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\ImgController;
use Session;
use Image;
use File;
use DB;


class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $user = User::find(Crypt::decrypt($id));
        return view('profiles.save')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileRequestUpdate $request, $id)
    {
        $user = User::find($id);        
        // Codigo para el logo
        $file = Input::file('avatar');
        if (File::exists($file))
        {        
            $img = Image::make($file)->encode('jpg');
            $user->avatar = base64_encode((new ImgController)->resize_image($img, 'jpg', 200, 200)); 
        }
        $user->name= $request->input('name');
        $user->email= $request->input('email');
        $user->save();
        return redirect()->route('home');
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
