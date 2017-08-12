<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Citizen extends Model
{
    protected $table = 'citizens';
    protected $dates = ['birthdate'];


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

    //*** Method ***
    public function age_discount(){
        $discount = Discount::find(1);
        if($this->age >= $discount->age){
            return true;
        }else{
            return false;
        }
        
    }    

    //*** Accessors ***  
    public function getAgeAttribute(){
        
        $birthdate = $this->birthdate;
        $age = $birthdate->diff(Carbon::now())->format('%y');
        return $age;
    }

}
