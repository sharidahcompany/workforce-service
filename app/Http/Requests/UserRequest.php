<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $userId = $this->route('employee')?->id ?? $this->route('employee');
   
        return [
            'branch_id'=>['nullable','exists:branches,id'],
            'department_id'=>['nullable','exists:departments,id'],
            'career_id'=>['nullable','exists:careers,id'],

            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $userId],
            'phone' => ['required', 'string', 'max:50','unique:users,phone,' . $userId],
            'id_number' => ['nullable', 'string', 'max:255','unique:users,id_number,' . $userId],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'full_name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'nationality' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
