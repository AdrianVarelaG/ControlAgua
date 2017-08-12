<?php

namespace App\Http\Requests\Rate;

use App\Http\Requests\Request;

class RateRequestUpdate extends Request
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
        return [
            'name' => 'required|max:50',
            'amount' => 'required|numeric|min:0'

        ];
    }

    public function messages()
    {
        return [
            'name.required'  => 'El nombre de la tarifa es obligatorio.',
            'amount.required'  => 'El monto de la tarifa es obligatorio.',
            'amount.numeric'  => 'El monto de la tarifa debe ser numérico.',
            'amount.min'  => 'El monto de la tarifa no puede ser negativo.'
        ];
    }

}