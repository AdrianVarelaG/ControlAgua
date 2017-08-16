<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Invoice extends Model
{
    protected $table = 'invoices';
    protected $dates = ['date', 'date_limit'];

    //*** Relations ***    
    public function citizen()
    {        
        return $this->belongsTo('App\Models\Citizen');
    }    

    public function contract()
    {        
        return $this->belongsTo('App\Models\Contract');
    }

    public function invoice_details()
    {        
        return $this->hasMany('App\Models\InvoiceDetail');
    }

    public function movement()
    {        
        return $this->hasOne('App\Models\Movement');
    }    
    
    public function payment()
    {        
        return $this->belongsTo('App\Models\Payment');
    }    
    
    public function reading()
    {        
        return $this->belongsTo('App\Models\Reading');
    }
    
    //*** Methods ***
    public function total_calculated(){

        $tot_credits = $this->invoice_details()
                            ->whereIn('movement_type', ['CA', 'CI', 'CT'])
                            ->sum('sub_total');
        $tot_debits = $this->invoice_details()
                            ->whereIn('movement_type', ['D', 'DE'])
                            ->sum('sub_total');        
        
        return $tot_credits-$tot_debits;
    }
    

    //*** Accesors ***
    public function getStatusDescriptionAttribute(){

        if($this->status=='P'){
            
            if($this->delayed_days>0){
                
            return 'Vencido';
                
            }else{
                
                return 'Pendiente';                              
            }        
        
        }else if ($this->status=='A') {
        
            return 'Abonado';
        
        }else if ($this->status=='C') {
        
            return 'Cancelado';
        
        }
    }

    public function getDelayedDaysAttribute(){
        
        $now = Carbon::now();
        $delayed_days = $this->date_limit->diffInDays($now, false);

        return $delayed_days;
    }

    public function getLabelStatusAttribute(){
        
        if($this->status =='C'){
        
            return 'label-primary';
        
        }elseif($this->status =='P'){
                            
            if($this->delayed_days>0){
                
            return 'label-danger';
                
            }else{
                
                return 'label-warning';                              
            }
        }
        
    }

}
