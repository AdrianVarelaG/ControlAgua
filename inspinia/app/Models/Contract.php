<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $table = 'contracts';
    protected $dates = ['date'];
    protected $guarded = ['id'];
	
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
        
    public function payments()
    {        
        return $this->hasMany('App\Models\Payment');
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
        return $this->movements()->where('movement_type', 'D');
    }

    public function credits()
    {        
        return $this->movements()->where('movement_type', 'C');
    }


    public function debits_range($initial_date, $final_date)
    {        
            return $this->movements()->where('movement_type', 'D')
                                    ->where('date', '>', $initial_date)
                                    ->where('date', '<=', $final_date);            
    }

    public function credits_range($initial_date, $final_date)
    {        
            return $this->movements()->where('movement_type', 'C')
                                    ->where('date', '>', $initial_date)
                                    ->where('date', '<=', $final_date);            
    }
    
    public function credits_from($date)
    {        
            return $this->movements()->where('movement_type', 'C')
                                    ->where('date', '>', $date);
    }
    
    public function debits_from($date)
    {        
            return $this->movements()->where('movement_type', 'D')
                                    ->where('date', '>', $date);
    }

    public function debits_date($date)
    {        
        return $this->movements()->where('movement_type', 'D')
                                ->where('date', '<=', $date);
    }

    public function credits_date($date)
    {        
        return $this->movements()->where('movement_type', 'C')
                                ->where('date', '<=', $date);
    }

    public function total_debits_date($date)
    {        
        $total_debits_date = ($this->debits_date($date))?$this->debits_date($date)->sum('amount'):0;
        
        return $total_debits_date;
    }

    public function total_credits_date($date)
    {        
        $total_credits_date = ($this->credits_date($date))?$this->credits_date($date)->sum('amount'):0;

        return $total_credits_date;
    }


    public function balance_date($date)
    {        
        $balance = $this->total_credits_date($date) - $this->total_debits_date($date);

        return round($balance,2);
    }
    

    //*** Accessors ***
    public function getCitizenNameAttribute(){
        
        return $this->citizen->name;
    }
    

    public function getTotalDebitsAttribute(){

        $tot_debits=($this->debits)?$this->debits->sum('amount'):0;

        return $tot_debits;
    }
    
    public function getTotalCreditsAttribute(){

        $tot_credits = ($this->credits)?$this->credits->sum('amount'):0;

        return $tot_credits;
    }

    public function getBalanceAttribute(){

        $balance = $this->total_credits - $this->total_debits;

        return round($balance,2);
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

    public function getLastInvoiceCanceledAttribute(){
        $last_invoice_canceled = $this->invoices()->where('status', 'C')
                                                    ->orderBy('year', 'DESC')
                                                    ->orderBy('month', 'DESC')->first();
        return $last_invoice_canceled;
    }

    public function getLastPaymentAttribute(){
        $last_payment = $this->payments()->orderBy('date', 'DESC')->first();
        
        return $last_payment;
    }

    public function getAddressAttribute(){
        
        $address = '';
        ($this->street != '')?$address = $this->street:'';
        ($this->number_ext != '')?$address = $address.' # '.$this->number_ext:'';
        ($this->number_int != '')?$address = $address.' - '.$this->number_int:'';
        ($this->neighborhood != '')?$address = $address.' '.$this->neighborhood:'';
                 
        return $address;
    }

}
