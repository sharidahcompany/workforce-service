<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReorderSprintStagesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'stages' => ['required', 'array'],
            'stages.*.id' => ['required', 'integer', 'exists:sprint_stages,id'],
            'stages.*.order' => ['required', 'integer', 'min:1'],
        ];
    }
}
