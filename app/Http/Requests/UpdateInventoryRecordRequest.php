<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInventoryRecordRequest extends FormRequest
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
            'commission_number' => ['sometimes', 'string', 'max:50', Rule::unique('inventory_records', 'commission_number')->ignore($this->route('inventoryRecord'))],
            'start_date' => ['sometimes', 'required', 'date'],
            'sector_id' => ['nullable', 'integer', 'exists:sectors,id'],
            'responsible_user_id' => ['sometimes', 'required', 'integer', 'exists:users,id'],
            'status' => ['nullable', 'string', 'in:Concluído,Reaberto,Em Andamento'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'commission_number.unique' => 'Este número de comissão já está em uso.',
            'start_date.required' => 'A data de início é obrigatória.',
            'responsible_user_id.required' => 'O responsável é obrigatório.',
            'status.in' => 'Status inválido.',
        ];
    }
}
