<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustodyLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'notes' => 'nullable|string',
            'termUrl' => 'nullable|string|max:500',
            'signedTermUrl' => 'nullable|string|max:500',
        ];
    }

    protected function passedValidation(): void
    {
        if ($this->has('termUrl')) {
            $this->merge(['term_url' => $this->termUrl]);
        }
        if ($this->has('signedTermUrl')) {
            $this->merge(['signed_term_url' => $this->signedTermUrl]);
        }
    }
}
