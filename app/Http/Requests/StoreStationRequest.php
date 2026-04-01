<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreStationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'dealer' => ['nullable', 'string', 'max:255'],
            'octane' => ['nullable', 'boolean'],
            'petrol' => ['nullable', 'boolean'],
            'diesel' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'octane' => $this->boolean('octane'),
            'petrol' => $this->boolean('petrol'),
            'diesel' => $this->boolean('diesel'),
        ]);
    }
}
