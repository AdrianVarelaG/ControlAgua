<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $dates = ['date'];

    //*** Relations ***    
    public function contract()
    {        
        return $this->belongsTo('App\Models\Contract');
    }   

    public function invoices()
    {        
        return $this->hasMany('App\Models\Invoice');
    }	

    public function payment_details()
    {        
        return $this->hasMany('App\Models\PaymentDetail');
    }   
    
    public function movements()
    {        
        return $this->hasMany('App\Models\Movement');
    }   

	//*** Accessors ***
    public function getTypeDescriptionAttribute(){
        
        if($this->type =='EF'){
        
            return 'Efectivo';
        
        }elseif($this->type =='CH'){
                            
            return 'Cheque';
                
        }elseif($this->type =='TA'){
                
            return 'Transferencia';                              
        }        
    }

}
