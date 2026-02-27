<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryRecordRequest extends FormRequest
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
            'commission_number' => ['nullable', 'string', 'max:50', 'unique:inventory_records,commission_number'],
            'start_date' => ['required', 'date'],
            'sector_id' => ['nullable', 'integer', 'exists:sectors,id'],
            'responsible_user_id' => ['required', 'integer', 'exists:users,id'],
            'status' => ['nullable', 'string', 'in:Concluído,Reaberto,Em Andamento'],
            'notes' => ['nullable', 'string', 'max:1000'],
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
            'commission_number.unique' => 'Este número de comissão já está em uso.',
            'start_date.required' => 'A data de início é obrigatória.',
            'start_date.date' => 'A data de início deve ser uma data válida.',
            'sector_id.exists' => 'O setor selecionado não existe.',
            'responsible_user_id.required' => 'O responsável é obrigatório.',
            'responsible_user_id.exists' => 'O usuário responsável selecionado não existe.',
            'status.in' => 'Status inválido.',
            'notes.max' => 'As observações não podem ter mais de 1000 caracteres.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Gerar número de comissão automático se não fornecido
        if (!$this->has('commission_number') || empty($this->commission_number)) {
            $lastRecord = \App\Models\InventoryRecord::orderBy('id', 'desc')->first();
            $nextNumber = $lastRecord ? ($lastRecord->id + 1) : 1;
            $this->merge([
                'commission_number' => 'INV-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT)
            ]);
        }

        // Garantir status padrão
        if (!$this->has('status')) {
            $this->merge(['status' => 'Em Andamento']);
        }
    }
}
