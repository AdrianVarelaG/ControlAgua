<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspector extends Model
{
    protected $table = 'inspectors';

	//*** Relations ***
    public function readings()
    {        
        return $this->hasMany('App\Models\Reading');
    }

}
