<?php

namespace App\Http\Requests\Reading;

use App\Http\Requests\Request;

class ReadingRequestUpdate extends Request
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
            'period' => 'required',
            'date' => 'required|date_format:d/m/Y',
            'inspector' => 'required',
            'previous_reading' => 'required|numeric|min:0',
            'current_reading' => 'required|numeric|greater_than:previous_reading'
        ];
    }

    public function messages()
    {
        return [
            'period.required'  => 'El mes y año de consumo es obligatorio.',
            'date.required'  => 'La fecha de lectura es obligatoria.',
            'inspector.required'  => 'Debe seleccionar un inspector.',
            'previous_reading.required'  => 'La lectura anterior es obligatoria.',
            'current_reading.required'  => 'La lectura actual es obligatoria.',
            'current_reading.greater_than'  => 'La lectura actual debe ser mayor que la lectura anterior.'
        ];
    }

}