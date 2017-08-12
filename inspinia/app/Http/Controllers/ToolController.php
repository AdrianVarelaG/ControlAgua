<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use File;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;


class ToolController extends Controller
{    
    public function format_ymd($date_dmy)
    {
        $date_ymd = substr($date_dmy, 6, 4).'-'.substr($date_dmy, 3, 2).'-'.substr($date_dmy, 0, 2);
        return $date_ymd;
    }
    
}
