<?php

namespace App\Http\Requests;

use App\Enums\MissionStatus;
use Illuminate\Foundation\Http\FormRequest;

class AttendancePermissionRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'approved_by' => ['nullable', 'integer', 'exists:users,id'],
            'deduct_from_balance' => ['nullable', 'boolean'],
            'reason' => ['nullable', 'string'],
            'start_datetime' => ['required', 'date'],
            'end_datetime' => ['required', 'date', 'after_or_equal:start_datetime'],
            'status' => ['nullable', 'in:' . implode(',', MissionStatus::values())],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'deduct_from_balance' => $this->deduct_from_balance ?? false,
            'status' => $this->status ?? MissionStatus::PENDING->value,
        ]);
    }
}
