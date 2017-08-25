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

    public function payments()
    {        
        return $this->hasMany('App\Models\Payment');
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
        
        foreach ($this->invoices->where('status', 'P') as $invoice) {
        
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
    
    public function getAgeAttribute(){
        
        $birthdate = $this->birthdate;
        $age = $birthdate->diff(Carbon::now())->format('%y');
        return $age;
    }

}
