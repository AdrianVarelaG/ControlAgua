<?php

namespace App\Http\Requests\Inspector;

use App\Http\Requests\Request;

class InspectorRequestStore extends Request
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
            'name' => 'required|min:3|max:50',
            'ID_number' => 'required|unique:inspectors',
            'email' => 'email|max:50'
        ];
    }

    public function messages()
    {
        return [
            'name.required'  => 'El nombre del inspector es obligatorio.',
            'ID_number.required'  => 'El número de identificación del inspector es obligatorio.',
            'ID_number.unique'  => 'El número de identificación ya fue registrado.'
        ];
    }

}