<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class UserRequestStore extends Request
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
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
            'role' => 'required'            
        ];
    }

    public function messages()
    {
        return [
            'name.required'  => 'El nombre de usuario es obligatorio.',
            'email.required'  => 'El correo electrónico es obligatorio.',
            'email.email'  => 'El correo electrónico no es un correo válido.',
            'email.unique'  => 'Ya existe un usuario registrado con ese correo electrónico.',
            'role.required'  => 'Debe seleccionar un rol.'                        
        ];
    }

}