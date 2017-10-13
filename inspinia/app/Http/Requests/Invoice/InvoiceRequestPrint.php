<?php

namespace App\Http\Requests\Invoice;

use App\Http\Requests\Request;

class InvoiceRequestPrint extends Request
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
        $max_invoice = strval(intval($this->request->get('invoice_from'))+499);
        
        return [
            'invoice_from' => 'required|numeric|min:0',
            'invoice_to' => 'required|numeric|greater_or_equal_than:invoice_from|max:'.$max_invoice
        ];
    }

    public function messages()
    {
        $max_invoice = strval(intval($this->request->get('invoice_from'))+499);

        return [
            'invoice_from.required'  => 'El Nro de Recibo inicial es obligatorio.',
            'invoice_to.required'  => 'El Nro de Recibo final es obligatorio.',
            'invoice_to.max'  => 'El Nro de Recibo final no debe ser mayor a '.$max_invoice,
            'invoice_to.greater_or_equal_than'  => 'El Nro de Recibo final debe ser mayor o igual que el Nro de Recibo inicial.'        
        ];
    }

}