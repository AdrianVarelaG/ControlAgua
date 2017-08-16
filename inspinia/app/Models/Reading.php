<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reading extends Model
{
    protected $table = 'readings';
    protected $dates = ['date'];    
        
    //*** Relations ***        
    public function inspector()
    {        
        return $this->belongsTo('App\Models\Inspector');
    }

    public function invoice()
    {        
        return $this->hasOne('App\Models\Invoice');
    }
    
    public function contract()
    {        
        return $this->belongsTo('App\Models\Contract');
    }

    //*** Methods ***
    public function exist($year, $month, $contract_id){
        $reading = Reading::where('year', $year)
                        ->where('month', $month)
                        ->where('contract_id', $contract_id);
        if($reading->count()>0){
            return true;
        }else{
            return false;
        }
    }
}
