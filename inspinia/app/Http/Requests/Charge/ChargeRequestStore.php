<?php

namespace App\Http\Requests\Charge;

use App\Http\Requests\Request;

class ChargeRequestStore extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //return false;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        
        $rules = [
            'description' => 'required|max:100|unique:charges',
        ];        
        
        if ($this->request->get('type') == 'M'){        
            
            $rules['amount'] = 'required|numeric|min:0';        
        
        }elseif ($this->request->get('type') == 'P'){

            $rules['percent'] = 'required|numeric|min:0|max:100';                
        }
        
        return $rules;

    }

    public function messages()
    {
        return [
            'amount.required'  => 'El monto del cargo es obligatorio.',
            'percent.required'  => 'El porcentaje del cargo es obligatorio.',
            'amount.numeric'  => 'El monto del cargo debe ser numérico.',
            'percent.numeric'  => 'El porcentaje del cargo debe ser numérico.',            
        ];
    }

}