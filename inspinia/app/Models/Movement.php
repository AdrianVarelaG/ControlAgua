<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Movement extends Model
{
    protected $table = 'movements';
    protected $dates = ['date'];
    protected $guarded = ['id'];

    //*** Relations ***    
    public function citizen()
    {        
        return $this->belongsTo('App\Models\Citizen');
    }
    
    public function contract()
    {        
        return $this->belongsTo('App\Models\Contract');
    }

    public function invoice()
    {        
        return $this->belongsTo('App\Models\Invoice');
    }

    public function payment()
    {        
        return $this->belongsTo('App\Models\Payment');
    }
    
}
