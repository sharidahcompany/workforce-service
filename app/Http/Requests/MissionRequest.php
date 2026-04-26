<?php

namespace App\Http\Requests;

use App\Enums\ApprovalStatus;
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
        $isPost = $this->isMethod('post');
        return [
            'title' => [$isPost ? 'required' : 'sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_datetime' => [$isPost ? 'required' : 'sometimes', 'date'],
            'end_datetime' => [$isPost ? 'required' : 'sometimes', 'date', 'after_or_equal:start_datetime'],
            'actual_start_datetime' => ['nullable', 'date'],
            'actual_end_datetime' => ['nullable', 'date', 'after_or_equal:actual_start_datetime'],
            'expense_amount' => ['nullable', 'numeric', 'min:0'],

            'assignee_ids' => ['nullable', 'array'],
            'assignee_ids.*' => ['integer', 'exists:users,id', 'distinct'],

            'status' => ['nullable', 'in:' . implode(',', MissionStatus::values())],
            'approval_status' => ['nullable', 'in:' . implode(',', ApprovalStatus::values())],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'expense_amount' => $this->expense_amount ?? 0,
            'assignee_ids' => $this->assignee_ids ?? [],
            'status' => $this->status ?? MissionStatus::PENDING->value,
            'approval_status' => $this->approval_status ?? ApprovalStatus::PENDING->value,
        ]);
    }
}
