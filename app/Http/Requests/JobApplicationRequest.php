<?php

namespace App\Http\Requests;

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
        $isPost = $this->isMethod('post');

        return [
            'job_post_id' => ['required', 'integer', 'exists:job_posts,id'],
            'user_id' => [$isPost ? 'required' : 'sometimes', 'integer', 'exists:users,id'],
            'status' => ['nullable', Rule::in(['pending', 'accepted', 'rejected'])],
            'full_name' => ['required', 'string', 'max:255'],
            'id_number' => ['required', 'string', 'max:255'],
            'nationality' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
        ];
    }
}
