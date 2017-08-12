<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discounts';
    protected $dates = ['initial_date', 'final_date'];
    
    
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
