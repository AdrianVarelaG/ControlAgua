<?php

namespace App\Http\Requests\Setting;

use App\Http\Requests\Request;

class SettingRequestStore extends Request
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
            'app_name' => 'required',
            'coin' => 'required',
            'money_format' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'app_name.required'  => 'El nombre de la aplicación es obligatorio.',
            'coin.required'  => 'El símbolo de la moneda es obligatorio.',
            'money_format.required'  => 'Debe seleccionar un formato.'
        ];
    }

}