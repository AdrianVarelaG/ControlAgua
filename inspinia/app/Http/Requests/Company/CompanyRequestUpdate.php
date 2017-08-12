<?php

namespace App\Http\Requests\Company;

use App\Http\Requests\Request;

class CompanyRequestUpdate extends Request
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
            'name' => 'required',
            'ID_company' => 'required',
            'address' => 'required',
            'company_phone' => 'required',
            'company_email' => 'required|email',
            'contact' => 'required',
            'contact_phone' => 'required',
            'contact_email' => 'required|email'            
        ];
    }

    public function messages()
    {
        return [
            'ID_company.required'  => 'El RIF de la empresa es obligatorio.',
            'company_phone.required'  => 'El campo teléfono de la empresa es obligatorio.',
            'company_email.required'  => 'El campo email de la empresa es obligatorio.',
            'company_email.email'  => 'El email de la empresa no es un correo válido.',
            'contact.required'  => 'El campo responsable es obligatorio.',
            'conatct_phone.required'  => 'El teléfono del contacto es obligatorio.',
            'contact_email.required'  => 'El email del contacto es obligatorio.',
            'contact_email.email'  => 'El email del contacto no es un correo válido.'
        ];
    }
}