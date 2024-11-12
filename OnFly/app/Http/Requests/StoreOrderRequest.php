<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
        return [
            'destination' => 'required',
            'departure' => 'required|date',
            'return' => 'nullable|date',
        ];
    }

    public function messages()
    {
        return [
            'destination.required' => 'O campo destino é obrigatório.',
            'departure.required' => 'A data de partida é obrigatória.',
            'departure.date' =>  'A data de partida deve ser uma data válida.',
            'return.date' => 'A data de retorno deve ser uma data válida.',
        ];
    }
}
