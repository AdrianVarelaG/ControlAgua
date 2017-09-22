<?php

namespace App\Http\Requests\Contract;

use App\Http\Requests\Request;

class ContractRequestStore extends Request
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
            'number' => 'required|unique:contracts',
            'date' => 'required|date_format:d/m/Y',
            'rate' => 'required',
            'administration' => 'required',
            'state' => 'required',
            'municipality' => 'required',
            'neighborhood' => 'required',
            'street' => 'required',
            'number_ext' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'number.required'  => 'El número de contrato es obligatorio.',
            'number.unique'  => 'El número de contrato ya fue registrado.',
            'date.required'  => 'La fecha del contrato es obligatoria.',
            'rate.required'  => 'Debe seleccionar una tarifa.',
            'state.required'  => 'Debe seleccionar un estado.',
            'municipality.required'  => 'Debe seleccionar un municipio.',
            'neighborhood.required'  => 'El barrio o colonia es obligatorio.',
            'street.required'  => 'La calle es obligatoria.',
            'number_ext.required'  => 'El número externo es obligatorio.'
        ];
    }

}