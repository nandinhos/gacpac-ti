<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'military_id' => ['required', 'string', 'max:20', 'regex:/^[A-Z0-9\-]+$/'],
            'password' => ['required', 'string', 'min:4', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'military_id.required' => 'O número militar é obrigatório.',
            'military_id.regex' => 'O número militar deve conter apenas letras maiúsculas, números e hífens.',
            'military_id.max' => 'O número militar não pode ter mais de 20 caracteres.',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter pelo menos 4 caracteres.',
            'password.max' => 'A senha não pode ter mais de 255 caracteres.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'military_id' => 'número militar',
            'password' => 'senha',
        ];
    }
}
