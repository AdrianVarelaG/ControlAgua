<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Citizen extends Model
{
    protected $table = 'citizens';

    //*** Relations ***
    
    public function contracts()
    {        
        return $this->hasMany('App\Models\Contract');
    }

    public function municipality()
    {        
    	return $this->belongsTo('App\Models\Municipality');
    }    

    public function state()
    {        
    	return $this->belongsTo('App\Models\State');
    }    

}
