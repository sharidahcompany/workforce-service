<?php

namespace App\Http\Requests;

use App\Enums\MissionApprovalStatus;
use App\Enums\MissionStatus;
use Illuminate\Foundation\Http\FormRequest;

class MissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */ public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'approved_by' => ['nullable', 'integer', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_datetime' => ['required', 'date'],
            'end_datetime' => ['required', 'date', 'after_or_equal:start_datetime'],
            'actual_start_datetime' => ['nullable', 'date'],
            'actual_end_datetime' => ['nullable', 'date', 'after_or_equal:actual_start_datetime'],
            'created_by' => ['required', 'integer', 'exists:users,id'],
            'expense_amount' => ['nullable', 'numeric', 'min:0'],

            'assignee_ids' => ['nullable', 'array'],
            'assignee_ids.*' => ['integer', 'exists:users,id', 'distinct'],

            'status' => ['nullable', 'in:' . implode(',', MissionStatus::values())],
            'approval_status' => ['nullable', 'in:' . implode(',', MissionApprovalStatus::values())],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'expense_amount' => $this->expense_amount ?? 0,
            'assignee_ids' => $this->assignee_ids ?? [],
            'status' => $this->status ?? MissionStatus::PENDING->value,
            'approval_status' => $this->approval_status ?? MissionApprovalStatus::PENDING->value,
        ]);
    }
}
