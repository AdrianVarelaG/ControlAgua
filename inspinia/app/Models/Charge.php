<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    protected $table = 'charges';
    
    
	//*** Accesors ***
    public function getTypeDescriptionAttribute(){

        if($this->type=='M'){
            return 'Monto Fijo';
        }else if ($this->type=='P') {
            return 'Porcentual';
    	}
    }

    public function getMovementTypeDescriptionAttribute(){

        if($this->movement_type=='CI'){
            return 'Cargo por Impuesto';
        }else if ($this->type=='CA') {
            return 'Cargo Adicional';
    	}
    }

}
