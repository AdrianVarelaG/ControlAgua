<?php

	
    //Helper for active class in side bar menu
    function set_active($path, $active = 'active') {

        return call_user_func_array('Request::is', (array)$path) ? $active : '';

    }

    function money_fmt($value)
	{
    	if (Session::get('money_format') == 'PC2'){
    		return number_format(round($value,2),2,',','.');
    	}else if (Session::get('money_format') == 'CP2'){
    		return number_format(round($value,2),2,'.',',');    	
    	}else{
    		return number_format(round($value,2),2,',','.');
    	}
	}

    function month_letter($month, $format)
    {        
        $month_letter = '';
        switch ($month) 
        {
            case 1:
            ($format=='lg')?$month_letter = 'Enero':$month_letter = 'Ene';
            break;
            case 2:
            ($format=='lg')?$month_letter = 'Febrero':$month_letter = 'Feb';
            break;
            case 3:
            ($format=='lg')?$month_letter = 'Marzo':$month_letter = 'Mar';
            break;
            case 4:
            ($format=='lg')?$month_letter = 'Abril':$month_letter = 'Abr';
            break;
            case 5:
            ($format=='lg')?$month_letter = 'Mayo':$month_letter = 'May';
            break;
            case 6:
            ($format=='lg')?$month_letter = 'Junio':$month_letter = 'Jun';
            break;
            case 7:
            ($format=='lg')?$month_letter = 'Julio':$month_letter = 'Jul';
            break;
            case 8:
            ($format=='lg')?$month_letter = 'Agosto':$month_letter = 'Ago';
            break;
            case 9:
            ($format=='lg')?$month_letter = 'Septiembre':$month_letter = 'Sep';
            break;
            case 10:
            ($format=='lg')?$month_letter = 'Octubre':$month_letter = 'Oct';
            break;
            case 11:
            ($format=='lg')?$month_letter = 'Noviembre':$month_letter = 'Nov';
            break;
            case 12:
            ($format=='lg')?$month_letter = 'Diciembre':$month_letter = 'Dic';
            break;
        }
        return $month_letter;
    }