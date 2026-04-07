<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MoveTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sprint_stage_id' => ['required', 'integer', 'exists:sprint_stages,id'],
            'order' => ['required', 'integer', 'min:1'],
        ];
    }
}
