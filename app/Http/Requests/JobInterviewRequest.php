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
            'parent_id'=>['nullable','integer','exists:job_interviews,id'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'interviewer_id' => ['nullable', 'integer', 'exists:users,id'],
            'scheduled_at' => ['nullable', 'date'],

            'interview_type' => [
                'required',
                Rule::in(array_map(fn(InterviewType $case) => $case->value, InterviewType::cases())),
                Rule::requiredIf(fn() => !empty(request('meeting_link')) && request('interview_type') !== 'online'),
                Rule::requiredIf(fn() => !empty(request('location')) && request('interview_type') !== 'on_site'),
            ],

            'meeting_link' => [
                'nullable',
                'string',
                'max:255',
                'required_if:interview_type,online', 
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
                'required_if:interview_type,on_site',
            ],
           
            'reschedule_reason' => ['nullable', 'string'],

            
            'hr_notes' => ['nullable', 'string'],

            'status' => [
                'nullable',
                Rule::in(array_map(fn(InterviewStatus $case) => $case->value, InterviewStatus::cases())),
            ],


            // 'technical_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            // 'communication_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            // 'attitude_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            // 'overall_score' => ['nullable', 'integer', 'min:0', 'max:100'],


        ];
    }
}
