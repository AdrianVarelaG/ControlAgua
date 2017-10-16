<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Padron extends Model
{
    protected $table = 'padron2';

    protected $fillable = ['nombre', 'contrato', 'cuenta', 'direccion', 'calle', 'nro_ext', 'nro_int', 'ultimo_mes', 'ultimo_recibo', 'meses_adeudo', 'adeudo', 'barrio', 'status', 'nota'];
    
}
