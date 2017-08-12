<?php

namespace App\Http\Requests\Municipality;

use App\Http\Requests\Request;

class MunicipalityRequestStore extends Request
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
            'state' => 'required',
            'name' => 'required|max:100|unique:municipalities,name,NULL,id,state_id,'.$this->request->get('state')

        ];
    }

    public function messages()
    {
        return [
            'state.required'  => 'Debe seleccionar un estado.',
            'name.unique'  => 'El municipio ya ha sido registrado para ese estado.'
        ];
    }

}