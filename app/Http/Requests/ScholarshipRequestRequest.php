<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ScholarshipRequestRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'scholarship_id' => ['nullable', 'exists:scholarships,id'],

            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],

            'price' => ['nullable', 'numeric', 'min:0'],
            'discount_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],

            'duration' => ['nullable', 'string', 'max:255'],

            'status' => ['sometimes', 'in:pending,approved,rejected'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => $this->user()->id,
            'status' => $this->status ?? 'pending',
        ]);
    }
}
