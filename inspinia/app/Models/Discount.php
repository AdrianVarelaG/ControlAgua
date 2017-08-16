<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Discount extends Model
{
    protected $table = 'discounts';
    protected $dates = ['initial_date', 'final_date'];
    
    
    //*** Relations ***      
    public function authorization()
    {        
        return $this->belongsTo('App\Models\Authorization');
    }    
	

    //*** Methods ***
    public function show_temporary(){

        $today = Carbon::now()->format('Ymd');
        if($this->temporary=='Y'){
            if($today >= $this->initial_date->format('Ymd') && $today <= $this->final_date->format('Ymd')){
                return true;                
            }else{
                return false;
            }
        }else{            
            return true;        
        }
    }

    //*** Accesors ***
    public function getTypeDescriptionAttribute(){

        if($this->type=='M'){
            return 'Monto Fijo';
        }else if ($this->type=='P') {
            return 'Porcentual';
    	}
    }

    public function getMovementTypeDescriptionAttribute(){

        if($this->movement_type=='DE'){
            return 'Descuento por Edad';
        }else if ($this->type=='DT') {
            return 'Descuento Temporal';
        }else if ($this->type=='DS') {
            return 'Descuento Especial';
        }

    }

}
