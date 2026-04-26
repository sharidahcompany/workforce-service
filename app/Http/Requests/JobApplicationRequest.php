<?php

namespace App\Http\Requests;

use App\Enums\JobApplicationStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobApplicationRequest extends FormRequest
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
            'career_post_id' => ['nullable', 'integer', 'exists:career_posts,id'],
            'status' => ['nullable', Rule::in(array_map(fn(JobApplicationStatus $case) => $case->value, JobApplicationStatus::cases())),],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ];
    }
}
