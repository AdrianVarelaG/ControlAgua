<?php

namespace App\Http\Requests\Authorization;

use App\Http\Requests\Request;

class AuthorizationRequestUpdate extends Request
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
            'name' => 'required|min:3|max:100',
            'position' => 'required|max:100',
            'email' => 'email|max:50'
        ];
    }

    public function messages()
    {
        return [
            'name.required'  => 'El nombre de la persona que autoriza es obligatorio.',
            'position.required'  => 'El cargo de la persona que autoriza es obligatorio.'
        ];
    }


}