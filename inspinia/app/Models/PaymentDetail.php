<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    protected $table = 'payment_details';

    //*** Relations ***    
    public function payment()
    {        
        return $this->belongsTo('App\Models\Payment');
    }   

	//*** Accessors ***
    public function getTypeDescriptionAttribute(){
        
        if($this->type =='D'){
        
            return 'Debito';
                        
        }elseif($this->type =='C'){
                
            return 'Credito';                              
        }        
    }

}
