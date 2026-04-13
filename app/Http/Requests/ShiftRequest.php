<?php

namespace App\Http\Requests;

use App\Enums\WeekDay;
use Illuminate\Foundation\Http\FormRequest;

class ShiftRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'work_days' => ['nullable', 'array'],
            'work_days.*' => ['integer', 'in:' . implode(',', WeekDay::values())],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'grace_period' => ['nullable', 'integer', 'min:0'],
            'overtime_rate' => ['nullable', 'numeric', 'min:0'],
            'deduction_rate' => ['nullable', 'numeric', 'min:0'],
            'user_ids' => ['nullable', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id', 'distinct'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'work_days' => $this->work_days ?? [],
            'grace_period' => $this->grace_period ?? 0,
            'overtime_allowed' => $this->overtime_allowed ?? true,
            'overtime_rate' => $this->overtime_rate ?? 1,
            'deduction_rate' => $this->deduction_rate ?? 1,
        ]);
    }
}
