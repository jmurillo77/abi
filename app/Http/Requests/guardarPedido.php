<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class guardarPedido extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'ciudad' => 'bail|required|regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\-\'\.\s]+$/',
            'cedula' => 'bail|required|numeric|digits:10',
            'nombres' => 'bail|required|string|regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\-\'\.\s]+$/',
            'courier' => 'bail|required',
            'estado' => 'bail|required',
        ];

        if (!empty($this->input('email'))) {
            $rules['email'] = 'bail|string|regex:/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/';
        }


        if (!empty($this->input('peso'))) {
            $rules['peso'] =  'bail|regex:/^\d+(\.\d{1,3})?$/';
            $rules['tarifa'] =  'bail|required';
        }



        return $rules;
    }

    public function messages(): array
    {

        return [
            'ciudad.required' => 'El campo es obligatorio.',
            'ciudad.regex' => 'El campo ciudad debe contener solo letras, espacios, guiones, apóstrofos y puntos.',
            'cedula.required' => 'El campo es obligatorio.',
            'cedula.numeric' => 'El campo cedula debe ser numérico.',
            'cedula.digits' => 'El campo cedula debe tener :digits dígitos.',
            'nombres.required' => 'El campo es obligatorio.',
            'nombres.regex' => 'El campo nombres debe contener solo letras, espacios, guiones, apóstrofos y puntos.',
            'courier.required' => 'El campo es obligatorio.',
            'estado.required' => 'El campo es obligatorio.',
            'peso.required' => 'El campo es obligatorio.',
            'peso.regex' => 'El campo peso debe ser un número válido.',
            'tarifa.required' => 'El campo es obligatorio.',
        ];
    }

    public function attributes(): array
    {
        return [
            'estado' => 'estado de compra',
            'courier' => 'cliente de:',
            'email' => 'correo',
        ];
    }
}
