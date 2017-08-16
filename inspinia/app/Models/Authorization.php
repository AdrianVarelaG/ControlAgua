<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Authorization extends Model
{
    protected $table = 'authorizations';

	//*** Relations ***
    public function discounts()
    {        
        return $this->hasMany('App\Models\Discount');
    }

}
