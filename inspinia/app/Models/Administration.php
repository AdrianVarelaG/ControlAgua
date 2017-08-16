<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administration extends Model
{
    protected $table = 'administrations';
    
	//*** Relations ***
    public function contracts()
    {        
        return $this->hasMany('App\Models\Contract');
    }

}
