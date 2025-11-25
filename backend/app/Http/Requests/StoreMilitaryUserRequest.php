<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMilitaryUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'rank' => ['required', 'string', Rule::in([
                'SOLDADO', 'CABO', 'TERCEIRO_SARGENTO', 'SEGUNDO_SARGENTO', 
                'PRIMEIRO_SARGENTO', 'SUBTENENTE', 'ASPIRANTE', 
                'SEGUNDO_TENENTE', 'PRIMEIRO_TENENTE', 'CAPITAO',
                'MAJOR', 'TENENTE_CORONEL', 'CORONEL', 'GENERAL'
            ])],
            'military_id' => ['required', 'string', 'max:20', 'unique:military_users,military_id', 'regex:/^[A-Z0-9\-]+$/'],
            'sector_id' => ['required', 'integer', 'exists:sectors,id'],
            'user_role' => ['required', 'string', Rule::in([
                'user', 'commission', 'admin'
            ])],
            'email' => ['nullable', 'email', 'max:100', 'unique:military_users,email'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[\d\(\)\-\s\+]+$/'],
            'is_active' => ['boolean'],
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
            'name.required' => 'O nome é obrigatório.',
            'name.max' => 'O nome não pode ter mais de 100 caracteres.',
            'rank.required' => 'O posto/graduação é obrigatório.',
            'rank.in' => 'O posto/graduação selecionado é inválido.',
            'military_id.required' => 'O número militar é obrigatório.',
            'military_id.unique' => 'Este número militar já está cadastrado.',
            'military_id.regex' => 'O número militar deve conter apenas letras maiúsculas, números e hífens.',
            'military_id.max' => 'O número militar não pode ter mais de 20 caracteres.',
            'sector_id.required' => 'O setor é obrigatório.',
            'sector_id.exists' => 'O setor selecionado não existe.',
            'role.required' => 'O perfil de acesso é obrigatório.',
            'role.in' => 'O perfil de acesso selecionado é inválido.',
            'email.email' => 'O email deve ter um formato válido.',
            'email.unique' => 'Este email já está cadastrado.',
            'email.max' => 'O email não pode ter mais de 100 caracteres.',
            'phone.regex' => 'O telefone deve conter apenas números, parênteses, hífens e espaços.',
            'phone.max' => 'O telefone não pode ter mais de 20 caracteres.',
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
            'name' => 'nome',
            'rank' => 'posto/graduação',
            'military_id' => 'número militar',
            'sector_id' => 'setor',
            'role' => 'perfil de acesso',
            'email' => 'email',
            'phone' => 'telefone',
            'is_active' => 'ativo',
        ];
    }
}
