<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use File;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use App\Models\Citizen;
use App\Models\Contract;


class ToolController extends Controller
{    
    public function format_ymd($date_dmy)
    {
        $date_ymd = substr($date_dmy, 6, 4).'-'.substr($date_dmy, 3, 2).'-'.substr($date_dmy, 0, 2);
        return $date_ymd;
    }
    
    public function citizens_ajax(Request $request){
       
        $term = $request->term ?:'';
        $tags = Citizen::where('name', 'like', '%'.$term.'%')->orderBy('name')->lists('name', 'id');
        $valid_tags = [];
        foreach ($tags as $id => $tag) {
            $valid_tags[] = ['id' => $id, 'text' => $tag];
        }
        return \Response::json($valid_tags);
    }

    public function contracts_active_ajax(Request $request){
       
        $term = $request->term ?:'';
        $tags = Contract::where('status', 'A')->where('number', 'like', '%'.$term.'%')->orderBy('number')->lists('number', 'id');
        $valid_tags = [];
        foreach ($tags as $id => $tag) {
            $valid_tags[] = ['id' => $id, 'text' => $tag];
        }
        return \Response::json($valid_tags);
    }

}
