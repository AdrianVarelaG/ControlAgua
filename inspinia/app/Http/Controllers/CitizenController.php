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
use Session;
use Carbon\Carbon;

class CitizenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        if(Session::get('citizens_view') == 'contact'){
            $citizens = Citizen::paginate(5);            
            $company = Company::first();
            return view('citizens.index2')->with('citizens', $citizens)
                                    ->with('company', $company);
        }else if(Session::get('citizens_view') == 'list'){
            $citizens = Citizen::orderBy('name')->get();            
            $company = Company::first();
            return view('citizens.index')->with('citizens', $citizens)
                                    ->with('company', $company);
        }
    }

    public function change_view($view){

        Session::put('citizens_view', $view);
        return redirect()->route('citizens.index');
    }
    

    public function invoices($citizen_id){

        $company = Company::first();        
        $citizen = Citizen::find(Crypt::decrypt($citizen_id));        
        return view('citizens.invoices')->with('citizen', $citizen)
                                    ->with('company', $company);
    }
    
    public function payments($citizen_id){

        $company = Company::first();        
        $citizen = Citizen::find(Crypt::decrypt($citizen_id));        
        return view('citizens.payments')->with('citizen', $citizen)
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
        $states = State::where('status', 'A')->orderBy('name')->lists('name','id');
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
        $citizen->birthdate= (new ToolController)->format_ymd($request->input('birthdate'));
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function balance($id, $period)
    {
        $company = Company::first();                  
        $citizen = Citizen::find(Crypt::decrypt($id));
        $initial_balance=0;
        $movements = $citizen->movements;
        $credits =0;
        $debits =0;
        switch ($period) 
        {
            case '3':
                $period_title = 'Estado de Cuenta últimos 3 meses';
                break;
            case '6':
                $period_title = 'Estado de Cuenta últimos 6 meses';
                break;
            case '12':
                $period_title = 'Estado de Cuenta últimos 12 meses';
                break;
            case 'all':
                $period_title = 'Estado de Cuenta Completo';
                break;
        }        
        if($period == '3'){
            $initial_date = Carbon::now()->subMonths(2)->startOfMonth();
            $credits = $citizen->credits()->where('date', '<' , $initial_date)->sum('amount');
            $debits = $citizen->debits()->where('date', '<' , $initial_date)->sum('amount');
            $initial_balance = $credits - $debits;
            $movements = $citizen->movements()->where('date','>=',$initial_date)
                                    ->orderBy('date')->get();
        }        
        else if($period == '6'){
            $initial_date = Carbon::now()->subMonths(5)->startOfMonth();
            $credits = $citizen->credits()->where('date', '<' , $initial_date)->sum('amount');
            $debits = $citizen->debits()->where('date', '<' , $initial_date)->sum('amount');
            $initial_balance = $credits - $debits;            
            $movements = $citizen->movements()->where('date','>=',$initial_date)
                                    ->orderBy('date')->get();

        }
        else if($period == '12'){
            $initial_date = Carbon::now()->subMonths(11)->startOfMonth();
            $credits = $citizen->credits()->where('date', '<' , $initial_date)->sum('amount');
            $debits = $citizen->debits()->where('date', '<' , $initial_date)->sum('amount');
            $initial_balance = $credits - $debits;            
            $movements = $citizen->movements()->where('date','>=',$initial_date)
                                    ->orderBy('date')->get();

        }
        else if($period == 'all'){
            $initial_date = Carbon::now();
            $movements = $citizen->movements()->orderBy('date')->get();
        }
                        
        return view('citizens.balance')->with('company', $company)
                                    ->with('citizen', $citizen)
                                    ->with('initial_balance', $initial_balance)
                                    ->with('initial_date', $initial_date)
                                    ->with('movements', $movements)
                                    ->with('period', $period)
                                    ->with('period_title', $period_title);

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
        $states = State::where('status', 'A')->orderBy('name')->lists('name','id');        
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
        $citizen->birthdate= (new ToolController)->format_ymd($request->input('birthdate'));
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
        //return $view;
        /**
        * Logica de eliminacion para no generar inconsistencia.
        */        
        $citizen = Citizen::find($id);
        if ($citizen->contracts->count() == 0){            
            $citizen->delete();
            return redirect()->route('citizens.index')->with('notity', 'delete');        
        }else{            
            return redirect()->route('citizens.index')->withErrors('No se puede eliminar el ciudadano. Existen <strong>'.$citizen->contracts->count().'</strong> contratos asociados a él. Debe primero eliminar los contratos asociados. Gracias...');            
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
