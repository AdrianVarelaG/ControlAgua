<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\Http\Requests\User\UserRequestStore;
use App\Http\Requests\User\UserRequestUpdate;
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

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();  
        $company = Company::first();
        return view('users.index')->with('users', $users)
                                    ->with('company', $company); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User();
        return view('users.save')->with('user', $user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequestStore $request)
    {
        $user = new User();        
        $file = Input::file('avatar');        
        if (!File::exists($file))
        {        
            $file = public_path()."/img/avatar_default.png";
        }
        $img = Image::make($file)->encode('jpg');
        $user->avatar = base64_encode((new ImgController)->resize_image($img, 'jpg', 200, 200));         
        $user->name= $request->input('name');
        $user->email= $request->input('email');
        $user->password= bcrypt($request->input('password'));
        $user->role= $request->input('role');
        $user->status= 'A';
        $user->created_by= Auth::user()->name;        
        $user->save();
        return redirect()->route('users.index')->with('notity', 'create');
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
        return view('users.save')->with('user', $user);  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequestUpdate $request, $id)
    {
        $user = User::find($id);        
        // Codigo para el logo
        $file = Input::file('avatar');        
        if (File::exists($file))
        {        
            $img = Image::make($file)->encode('jpeg');
            $user->avatar = base64_encode((new ImgController)->resize_image($img, 'jpg', 200, 200)); 
        }
        $user->name= $request->input('name');
        $user->email= $request->input('email');
        $user->role= $request->input('role');
        if($request->input('change_password')){
            $user->password= bcrypt($request->input('password'));
        }
        $user->save();
        return redirect()->route('users.index')->with('notity', 'update');
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
        $user = User::find($id);
        $user->delete();
        return redirect()->route('users.index')->with('notity', 'delete');
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
        $user = User::find(Crypt::decrypt($id));
        ($user->status == "A")?$user->status="D":$user->status= "A";  
        $user->save();
        return redirect()->route('users.index');
    }

}
