<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class editarPedido extends FormRequest
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
            'ciudad' => 'bail|regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\-\'\.\s]+$/',
            'cedula' => 'bail|numeric|digits:10',
            'nombres' => 'bail|string|regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\-\'\.\s]+$/',
            'InputCourier_edit' => 'bail|string',
            'InputEstado_edit' => 'bail|string',
            'InputPeso_edit' => 'bail|regex:/^\d+(\.\d{1,3})?$/',
            'InputTarifa_edit' => 'bail|string',
        ];

        if (!empty($this->input('email'))) {
            $rules['email'] = 'bail|string|regex:/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/';
        }
        return $rules;
    }

    public function messages(): array
    {
        return [
            'ciudad.regex' => 'El campo ciudad solo puede contener letras, espacios, guiones y puntos.',
            'cedula.numeric' => 'El campo cedula debe ser numérico.',
            'cedula.digits' => 'El campo cedula debe tener 10 dígitos.',
            'nombres.regex' => 'El campo nombres solo puede contener letras, espacios, guiones y puntos.',
            'email.regex' => 'El formato del campo email no es válido.',
            'InputPeso_edit.regex' => 'El campo peso debe ser un número válido con hasta dos decimales.'
        ];
    }

    public function attributes(): array
    {
        return [
            'estado' => 'estado de compra',
            'courier' => 'servicio de mensajería',
            'email' => 'correo',
        ];
    }
}
