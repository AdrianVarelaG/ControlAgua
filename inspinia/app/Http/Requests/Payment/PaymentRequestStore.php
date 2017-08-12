<?php

namespace App\Http\Requests\Payment;

use App\Http\Requests\Request;

class PaymentRequestStore extends Request
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
            'date' => 'required|date_format:d/m/Y',
            'type' => 'required',
            'invoices' => 'required'            
        ];        
                
        return $rules;        
    }

    public function messages()
    {
        return [
            'date.required'  => 'La fecha del pago es obligatoria.',
            'date.date_format'  => 'La fecha del pago no es una fecha valida.',
            'type.required'  => 'Debe seleccionar un tipo de pago.',
            'invoices.required'  => 'Debe seleccionar al menos un recibo a pagar.'
        ];
    }

}