<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    protected $table = 'invoice_details';
    
	//*** Relations ***    
    public function invoice()
    {        
        return $this->belongsTo('App\Models\Invoice');
    }
}
