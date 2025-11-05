<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckinCustodyLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'checkinDate' => 'required|date',
            'signedTermUrl' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'checkinDate.required' => 'A data de devolução (check-in) é obrigatória.',
        ];
    }

    protected function passedValidation(): void
    {
        $this->merge([
            'checkin_date' => $this->checkinDate,
        ]);

        if ($this->has('signedTermUrl')) {
            $this->merge(['signed_term_url' => $this->signedTermUrl]);
        }
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $custody = $this->route('custody');
            if ($custody && $custody->checkin_date) {
                $validator->errors()->add('checkinDate', 'Esta cautela já foi devolvida.');
            }
        });
    }
}
