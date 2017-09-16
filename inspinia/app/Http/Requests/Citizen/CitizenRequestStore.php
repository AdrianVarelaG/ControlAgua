<?php

namespace App\Http\Requests\Citizen;

use App\Http\Requests\Request;

class CitizenRequestStore extends Request
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
            'ID_number' => 'max:18|unique:citizens',
            'name' => 'required|min:3|max:50',            
            'RFC' => 'required',
            'email' => 'email|max:50',
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
            'name.required'  => 'El nombre del ciudadano es obligatorio.',
            'ID_number.max'  => 'El CURP debe tener un máximo de 18 caracteres.',
            'ID_number.unique'  => 'El CURP ya fue registrado.',
            'RFC.required'  => 'El RFC es obligatorio.',
            'state.required'  => 'Debe seleccionar un estado.',
            'municipality.required'  => 'Debe seleccionar un municipio.',
            'neighborhood.required'  => 'El Bario o Colonia es obligatorio.',
            'street.required'  => 'La calle es obligatoria.',
            'number_ext.required'  => 'El número externo es obligatorio.'
        ];
    }

}