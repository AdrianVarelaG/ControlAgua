<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Session;
use Auth;
use App\Models\Condominium;
use App\Models\AppPayment;
use App\Models\Payment;

class SystemComposer
{
    /**
     * Create a movie composer.
     *
     * @return void
     */
    public function __construct()
    {
       
        if (Session::has('condominium_id')){
            $this->condominium = Condominium::find(Session::get('condominium_id'));
        }
        if(Session::get('user_system_role') == 'ALFA'){
            $this->tot_app_payments_standby_global = AppPayment::by_status('S')->count();
        }
        if(Session::get('user_system_role') == 'ADMIN'){
            $this->tot_payments_standby_global = Payment::payments_condominium_status(Session::get('condominium_id'), 'S')->count();
        }


    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if (Session::has('condominium_id')){        
            $view->with('condominium', $this->condominium);
        }
        if(Session::get('user_system_role') == 'ALFA'){
            $view->with('tot_app_payments_standby_global', $this->tot_app_payments_standby_global);   
        }
        if(Session::get('user_system_role') == 'ADMIN'){
            $view->with('tot_payments_standby_global', $this->tot_payments_standby_global);   
        }

    }
}