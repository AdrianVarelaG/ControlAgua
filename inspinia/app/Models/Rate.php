<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $table = 'rates';
	
    
    public function contracts()
    {        
        return $this->hasMany('App\Models\Contract');
    }

}
