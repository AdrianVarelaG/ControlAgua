<?php

namespace App\Http\Requests\State;

use App\Http\Requests\Request;

class StateRequestStore extends Request
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
            'name' => 'required|max:100|unique:states'

        ];
    }

    public function messages()
    {
        return [
            'name.unique'  => 'El estado ya ha sido registrado.'
        ];
    }

}