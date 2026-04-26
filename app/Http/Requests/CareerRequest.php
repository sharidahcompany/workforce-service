<?php

namespace App\Http\Requests;

use App\Enums\CareerStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CareerRequest extends FormRequest
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
        return [
            'department_id'=>['required','exists:departments,id'],
            'name' => ['required', 'array'],
            'name.*' => ['required','string','max:255'],
            'description' => ['required', 'array'],
            'description.*' => ['required','string','max:255'],
            'benefits' => ['nullable', 'array'],
            'benefits.*' => ['required','integer','exists:benefits,id'],
            'status'=>[
                'nullable', 
                    Rule::in(array_map(fn(CareerStatus $case) => $case->value, CareerStatus::cases())),
            ],
            'cover' =>['nullable','image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
