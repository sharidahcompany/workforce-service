<?php

namespace App\Http\Requests;

use App\Enums\ApprovalStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobOfferRequest extends FormRequest
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
            'job_application_id'=>['nullable','exists:job_applications,id'],
            'career_id'=>['required','integer','exists:careers,id'],
            'user_id'=>['required','integer','exists:users,id'],
            'salary' =>['required','numeric'],
            'status'=>['nullable', 
                Rule::in(array_map(fn(ApprovalStatus $case) => $case->value, ApprovalStatus::cases())),
            ]
        ];
    }
}
