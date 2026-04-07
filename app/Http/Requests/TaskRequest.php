<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
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
        $isPost = $this->isMethod('post');

        return [
            'sprint_stage_id' => [$isPost ? 'required' : 'sometimes', 'exists:sprint_stages,id'],
            'name' => [$isPost ? 'required' : 'sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string', 'nullable'],
            'order' => [$isPost ? 'required' : 'sometimes', 'integer', 'min:0'],
            'priority' => [$isPost ? 'required' : 'sometimes', Rule::in(['low', 'medium', 'high'])],
            'assigned_by' => ['nullable', 'exists:users,id'],
            'users' => ['nullable', 'array'],
            'users.*' => ['exists:users,id'],
            'start_date' => [$isPost ? 'required' : 'sometimes', 'date'],
            'due_date' => [$isPost ? 'required' : 'sometimes', 'date', 'after_or_equal:' . ($this->start_date ?? now()),],
            'end_date' => ['nullable', 'date', 'after_or_equal:' . ($this->start_date ?? now()),],
        ];
    }
}
