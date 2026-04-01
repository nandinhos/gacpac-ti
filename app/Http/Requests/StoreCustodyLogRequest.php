<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustodyLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cautelaNumber' => 'required|string|max:50|unique:custody_logs,cautela_number',
            'userId' => 'required|string|exists:users,id',
            'checkoutDate' => 'required|date',
            'assetIds' => 'required|array|min:1',
            'assetIds.*' => 'string|exists:assets,id',
            'termUrl' => 'nullable|string|max:500',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'cautelaNumber.required' => 'O número da cautela é obrigatório.',
            'cautelaNumber.unique' => 'Este número de cautela já está em uso.',
            'userId.required' => 'O usuário é obrigatório.',
            'checkoutDate.required' => 'A data de checkout é obrigatória.',
            'assetIds.required' => 'É necessário selecionar pelo menos um ativo.',
            'assetIds.min' => 'É necessário selecionar pelo menos um ativo.',
        ];
    }

    /**
     * After validation, merge snake_case keys for the controller.
     */
    protected function passedValidation(): void
    {
        $this->merge([
            'cautela_number' => $this->cautelaNumber,
            'user_id' => $this->userId,
            'checkout_date' => $this->checkoutDate,
            'term_url' => $this->termUrl,
        ]);
    }
}
