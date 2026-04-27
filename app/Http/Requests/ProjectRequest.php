<?php

namespace App\Http\Requests;

use App\Enums\ProjectManagment\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $requiredOrSometimes = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'name' => [$requiredOrSometimes, 'string', 'max:255'],
            'description' => [$requiredOrSometimes, 'string'],
            'start_date' => [$requiredOrSometimes, 'date'],
            'end_date' => [$requiredOrSometimes, 'date', 'after_or_equal:start_date'],
            'notes' => ['nullable', 'string'],
            'status'=>['nullable',Rule::in(array_map(fn(ProjectStatus $case) => $case->value, ProjectStatus::cases()))],
        ];
    }
}
