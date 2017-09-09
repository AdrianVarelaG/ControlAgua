<?php

namespace App\Http\Requests\Payment;

use App\Http\Requests\Request;

class PaymentRequestUpdate extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'folio' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'folio.required'  => 'El número del folio es obligatorio.',
        ];
    }

}