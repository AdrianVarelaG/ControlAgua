<?php

namespace App\Http\Requests\Contract;

use App\Http\Requests\Request;

class ContractRequestActivate extends Request
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
            'date' => 'required|date_format:d/m/Y',
            'initial_balance' => 'required',
            'date_last_payment' => 'required|date_format:d/m/Y',
            'initial_balance' => 'required|numeric'            
        ];
    }

    public function messages()
    {
        return [
            'date.required'  => 'La fecha del saldo inicial es obligatoria.',
            'date.format'  => 'La fecha del saldo inicial debe tener un formato válido.',
            'date_last_payment.required'  => 'La fecha del último pago es obligatoria.',
            'date_last_payment.format'  => 'La fecha del último pago debe tener un formato válido.',
            'initial_balance.required'  => 'El saldo inicial es obligatorio.',
            'initial_balance.numeric'  => 'El saldo inicial debe ser numérico.'
        ];
    }

}