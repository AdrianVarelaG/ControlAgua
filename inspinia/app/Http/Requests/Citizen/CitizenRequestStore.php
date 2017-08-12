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
            'ID_number' => 'required|unique:citizens',
            'name' => 'required|min:3|max:50',            
            'RFC' => 'required',
            'email' => 'email|max:50',
            'state' => 'required',
            'municipality' => 'required',
            'neighborhood' => 'required',
            'street' => 'required',
            'number_ext' => 'required',
            'number_int' => 'required',
            'postal_code' => 'required'            
        ];
    }

    public function messages()
    {
        return [
            'name.required'  => 'El nombre del ciudadano es obligatorio.',
            'ID_number.required'  => 'El número de identificación del ciudadano es obligatorio.',
            'ID_number.unique'  => 'El número de identificación ya fue registrado.',
            'RFC.required'  => 'El RFC es obligatorio.',
            'state.required'  => 'Debe seleccionar un estado.',
            'municipality.required'  => 'Debe seleccionar un municipio.',
            'neighborhood.required'  => 'El Bario o Colonia es obligatorio.',
            'street.required'  => 'La calle es obligatoria.',
            'number_ext.required'  => 'El número externo es obligatorio.',
            'number_int.required'  => 'El número interno es obligatorio.',
            'postal_code.required'  => 'El código postal es obligatorio.'            
        ];
    }

}