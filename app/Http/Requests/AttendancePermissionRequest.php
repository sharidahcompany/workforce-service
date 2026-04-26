<?php

namespace App\Http\Requests;

use App\Enums\ApprovalStatus;
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
        $isPost = $this->isMethod('post');
        return [
            'approved_by' => ['nullable', 'integer', 'exists:users,id'],
            'deduct_from_balance' => ['nullable', 'boolean'],
            'reason' => ['nullable', 'string'],
            'start_datetime' => [$isPost ? 'required' : 'sometimes', 'date'],
            'end_datetime' => [$isPost ? 'required' : 'sometimes', 'date', 'after_or_equal:start_datetime'],
            'status' => ['nullable', 'in:' . implode(',', ApprovalStatus::values())],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'deduct_from_balance' => $this->deduct_from_balance ?? false,
            'status' => $this->status ?? ApprovalStatus::PENDING->value,
        ]);
    }
}
