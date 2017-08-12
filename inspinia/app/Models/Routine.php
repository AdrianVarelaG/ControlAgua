<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Routine extends Model
{
    protected $table = 'routines';
    protected $dates = ['date'];    


    //*** Accesors ***
    public function getTypeDescriptionAttribute(){

        if($this->rate_type=='F'){
                            
            return 'Monto Fijo';
                        
        }else if ($this->rate_type=='C') {
        
            return 'Monto por Consumo';
                
        }
    }

}
