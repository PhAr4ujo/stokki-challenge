<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CarrierRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'cnpj' => preg_replace('/\D+/', '', (string) $this->input('cnpj')),
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:255'],
            'cnpj'             => ['required', 'regex:/^\d{14}$/'],
            'street'           => ['required', 'string', 'max:255'],
            'city'             => ['required', 'string', 'max:255'],
            'state'            => ['required', 'string'],
            'neighborhood'     => ['required', 'string', 'max:255'],
            'residence_number' => ['required', 'integer', 'min:0'],
            'complement'       => ['required', 'string', 'max:255'],
        ];
    }
}
