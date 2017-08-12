<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $table = 'contracts';
    protected $dates = ['date'];
	
    public function citizen()
    {        
    	return $this->belongsTo('App\Models\Citizen');
    }    
        
    public function rate()
    {        
    	return $this->belongsTo('App\Models\Rate');
    }    

    public function administration()
    {        
    	return $this->belongsTo('App\Models\Administration');
    }    

    public function state()
    {        
    	return $this->belongsTo('App\Models\State');
    }    

    public function municipality()
    {        
    	return $this->belongsTo('App\Models\Municipality');
    }    

}
