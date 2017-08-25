<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    //*** Accesors ***
    public function getRoleDescriptionAttribute(){
        
        if ($this->role == 'ADM'){        
            return "Administrador";
        }else if($this->role == 'OPE'){
            return "Operador";
        }else if($this->role == 'CAJ'){
            return "Cajero";
        }else if($this->role == 'DDA'){
            return "Departamento de Aguas";
        }
    }

}
