<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Movement extends Model
{
    protected $table = 'movements';
    protected $dates = ['date'];

    //*** Relations ***    
    public function contracts()
    {        
        return $this->hasMany('App\Models\Contract');
    }

    public function invoices()
    {        
        return $this->hasMany('App\Models\Invoice');
    }

    public function payments()
    {        
        return $this->hasMany('App\Models\Payment');
    }
    
}
