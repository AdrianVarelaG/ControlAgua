<?php

namespace App\Http\Requests\Discount;

use App\Http\Requests\Request;

class DiscountRequestUpdate extends Request
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
            'description' => 'required|max:100',
        ];        
        
        if ($this->request->get('type') == 'M'){        
            
            $rules['amount'] = 'required|numeric|min:0';        
        
        }elseif ($this->request->get('type') == 'P'){

            $rules['percent'] = 'required|numeric|min:0|max:100';                
        }
        
        return $rules;    }

    public function messages()
    {
        return [
            'amount.required'  => 'El monto del descuento es obligatorio.',
            'percent.required'  => 'El porcentaje del descuento es obligatorio.',
            'amount.numeric'  => 'El monto del descuento debe ser numérico.',
            'percent.numeric'  => 'El porcentaje del descuento debe ser numérico.',            
        ];
    }

}