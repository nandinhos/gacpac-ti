<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssetRequest extends FormRequest
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
        $assetId = $this->route('asset');
        
        return [
            'brand' => ['sometimes', 'required', 'string', 'max:100'],
            'model' => ['sometimes', 'required', 'string', 'max:100'],
            'serial_number' => ['nullable', 'string', 'max:100', Rule::unique('assets')->ignore($assetId)],
            'patrimony_number' => ['nullable', 'string', 'max:50', Rule::unique('assets')->ignore($assetId)],
            'type' => ['sometimes', 'required', 'string', Rule::in([
                'COMPUTADOR', 'NOTEBOOK', 'MONITOR', 'TECLADO', 'MOUSE', 
                'IMPRESSORA', 'SCANNER', 'ROTEADOR', 'SWITCH', 'SERVIDOR',
                'TELEFONE', 'CELULAR', 'TABLET', 'PROJETOR', 'CAMERA',
                'HD_EXTERNO', 'PENDRIVE', 'OUTROS'
            ])],
            'category' => ['sometimes', 'required', 'string', Rule::in([
                'COMPUTACAO', 'PERIFERICOS', 'REDE', 'COMUNICACAO', 
                'AUDIOVISUAL', 'ARMAZENAMENTO', 'OUTROS'
            ])],
            'status' => ['sometimes', 'required', 'string', Rule::in([
                'DISPONIVEL', 'EM_USO', 'MANUTENCAO', 'BAIXADO', 'EXTRAVIADO'
            ])],
            'condition' => ['sometimes', 'required', 'string', Rule::in([
                'NOVO', 'BOM', 'REGULAR', 'RUIM', 'INSERVIVEL'
            ])],
            'sector_id' => ['sometimes', 'required', 'integer', 'exists:sectors,id'],
            'acquisition_date' => ['nullable', 'date', 'before_or_equal:today'],
            'warranty_expiry' => ['nullable', 'date', 'after:acquisition_date'],
            'purchase_value' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
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
            'brand.required' => 'A marca é obrigatória.',
            'model.required' => 'O modelo é obrigatório.',
            'type.required' => 'O tipo é obrigatório.',
            'type.in' => 'O tipo selecionado é inválido.',
            'category.required' => 'A categoria é obrigatória.',
            'category.in' => 'A categoria selecionada é inválida.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status selecionado é inválido.',
            'condition.required' => 'A condição é obrigatória.',
            'condition.in' => 'A condição selecionada é inválida.',
            'sector_id.required' => 'O setor é obrigatório.',
            'sector_id.exists' => 'O setor selecionado não existe.',
            'serial_number.unique' => 'Este número de série já está cadastrado.',
            'patrimony_number.unique' => 'Este número de patrimônio já está cadastrado.',
            'acquisition_date.before_or_equal' => 'A data de aquisição não pode ser futura.',
            'warranty_expiry.after' => 'A data de vencimento da garantia deve ser posterior à data de aquisição.',
            'purchase_value.min' => 'O valor de compra deve ser positivo.',
            'purchase_value.max' => 'O valor de compra não pode exceder R$ 999.999,99.',
            'notes.max' => 'As observações não podem ter mais de 1000 caracteres.',
        ];
    }
}
