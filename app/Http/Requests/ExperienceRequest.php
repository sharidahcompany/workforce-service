<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExperienceRequest extends FormRequest
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
        $isPost = $this->isMethod('POST');
        return [
            'user_id' => [$isPost ? 'required' : 'sometimes', 'integer', 'exists:users,id'],
            'experiences' => ['required', 'array', 'min:1'],
            'experiences.*.title'        => ['required', 'string', 'max:255'],
            'experiences.*.organization' => ['required', 'string', 'max:255'],
            'experiences.*.description'  => ['nullable', 'string'],
            'experiences.*.start_date'   => ['required', 'date'],
            'experiences.*.end_date'     => ['nullable', 'date', 'after_or_equal:experiences.*.start_date'],
            'experiences.*.type'         => ['nullable', Rule::in(['work', 'education'])],
        ];
    }
}
