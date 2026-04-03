<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SprintRequest extends FormRequest
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
            'project_id' => [$requiredOrSometimes, 'exists:projects,id'],
            'name' => [$requiredOrSometimes, 'string', 'max:255'],
            'start_date' => [$requiredOrSometimes, 'date'],
            'end_date' => [$requiredOrSometimes, 'date', 'after_or_equal:start_date'],
        ];
    }
}
