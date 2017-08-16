<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $table = 'contracts';
    protected $dates = ['date'];
	
    //*** Relations ***      
    public function administration()
    {        
        return $this->belongsTo('App\Models\Administration');
    }    

    public function citizen()
    {        
        return $this->belongsTo('App\Models\Citizen');
    }    

    public function invoices()
    {        
        return $this->hasMany('App\Models\Invoice');
    }
    
    public function movements()
    {        
        return $this->hasMany('App\Models\Movement');
    }
    
    public function municipality()
    {        
        return $this->belongsTo('App\Models\Municipality');
    }    
        
    public function rate()
    {        
    	return $this->belongsTo('App\Models\Rate');
    }    

    public function readings()
    {        
        return $this->hasMany('App\Models\Reading');
    }
    
    public function state()
    {        
    	return $this->belongsTo('App\Models\State');
    }    
    
    //Methods
    public function debits()
    {        
        return $this->hasMany('App\Models\Movement')->where('movement_type', 'D');
    }

    public function credits()
    {        
        return $this->hasMany('App\Models\Movement')->where('movement_type', 'C');
    }

    //*** Accessors ***
    public function getTotalDebitsAttribute(){

        $tot_debits = $this->debits->sum('amount');

        return $tot_debits;
    }
    
    public function getTotalCreditsAttribute(){

        $tot_credits = $this->credits->sum('amount');

        return $tot_credits;
    }

    public function getBalanceAttribute(){

        $balance = $this->total_credits - $this->total_debits;

        return $balance;
    }

    public function getExpiredInvoicesAttribute(){

        $expired_invoices=0;
        
        foreach ($this->invoices as $invoice) {
        
             if($invoice->delayed_days > 0){
                $expired_invoices = $expired_invoices + 1;
             }
        }                
        
        return $expired_invoices;
    }


    public function getStatusDescriptionAttribute(){

        if($this->balance >0){
            
            if($this->expired_invoices > 0){
                
                return 'Moroso';
                
            }else{
                
                return 'Pendiente';                              
            }        
        
        }else{
        
            return 'Solvente';
        }        
   }
    
    public function getLabelStatusAttribute(){
        
        if($this->status_description =='Solvente'){
        
            return 'label-primary';
        
        }elseif($this->status_description =='Moroso'){
                            
            return 'label-danger';
                
        }elseif($this->status_description =='Pendiente'){
                
            return 'label-warning';                              
        }        
    }

}
