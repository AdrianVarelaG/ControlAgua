<?php

namespace App\Http\Requests\Administration;

use App\Http\Requests\Request;

class AdministrationRequestUpdate extends Request
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
            'period' => 'required|max:100',
            'authority' => 'required|max:100',
            'position' => 'required|max:100'
        ];
    }

    public function messages()
    {
        return [
            'period.unique'  => 'El período ya ha sido registrado.',
            'period.required'  => 'El período es obligatorio.',
            'authority.required'  => 'La autoridad es obligatoria.',
            'position.required'  => 'El cargo de la autoridad es obligatorio.'
        ];
    }

}