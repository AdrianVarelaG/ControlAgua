<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Models\Setting;
use App\User;
use Session;


class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }else{
            
            //Si el usuario esta autenticado y activado. Setear variable de sesion para saber el condominio a manejar            
            if(Auth::user()->status !='A') {
                $usuario = Auth::user()->name;
                Auth::logout();
                return redirect('login')->withErrors(array('global' => "Lo sentimos ".$usuario.", su cuenta está desactivada. Comuniquese con nuestros administradores."));
            }else{        
                //Seteo de variables de sesion
                $company = Company::first();
                $setting = Setting::first();
                $user = User::find(Auth::user()->id);
                Session::put('user_role', $user->role);
                Session::put('company_name', $company->name);
                Session::put('app_name', $setting->app_name);
                Session::put('coin', $setting->coin);
                Session::put('money_format', $setting->money_format);
                Session::put('create_notification', $setting->create_notification);
                Session::put('update_notification', $setting->update_notification);
                Session::put('delete_notification', $setting->delete_notification);         
            }
        }
        return $next($request);
    }
}
