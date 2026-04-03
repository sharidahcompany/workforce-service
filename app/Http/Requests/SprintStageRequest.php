<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SprintStageRequest extends FormRequest
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
            'sprint_id' => [$requiredOrSometimes, 'exists:sprints,id'],
            'name' => [$requiredOrSometimes, 'string', 'max:255'],
            'order' => [$requiredOrSometimes, 'integer', 'min:0'],
        ];
    }
}
