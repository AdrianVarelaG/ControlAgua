<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'states';
    
    public function citizens()
    {        
        return $this->hasMany('App\Models\Citizen');
    }
    
    public function contracts()
    {        
        return $this->hasMany('App\Models\Contract');
    }

    public function municipalities()
    {        
        return $this->hasMany('App\Models\Municipality');
    }

}
