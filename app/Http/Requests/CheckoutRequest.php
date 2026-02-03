<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'session' => ['required', 'date'],
            'seats' => ['required', 'array', 'min:1'],
            'seats.*' => ['required', 'string', 'regex:/^[A-E]-([1-9]|10)$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'session.required' => 'Por favor, selecione uma sessão.',
            'session.date' => 'A sessão selecionada é inválida.',
            'seats.required' => 'Por favor, selecione pelo menos uma cadeira.',
            'seats.min' => 'Por favor, selecione pelo menos uma cadeira.',
            'seats.*.regex' => 'A cadeira :input não é válida.',
        ];
    }
}
