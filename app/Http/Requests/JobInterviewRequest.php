<?php

namespace App\Http\Requests;

use App\Enums\InterviewStatus;
use App\Enums\InterviewType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobInterviewRequest extends FormRequest
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
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'interviewer_id' => ['nullable', 'integer', 'exists:users,id'],

            'interview_type' => [
                'required',
                Rule::in(array_map(fn(InterviewType $case) => $case->value, InterviewType::cases())),
            ],

            'scheduled_at' => ['required', 'date'],

            'meeting_link' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],

            'reschedule_reason' => ['nullable', 'string'],

            'technical_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'communication_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'attitude_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'overall_score' => ['nullable', 'integer', 'min:0', 'max:100'],

            'hr_notes' => ['nullable', 'string'],

            'status' => [
                'nullable',
                Rule::in(array_map(fn(InterviewStatus $case) => $case->value, InterviewStatus::cases())),
            ],
        ];
    }
}
