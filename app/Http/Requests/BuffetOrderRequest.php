<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuffetOrderRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:pending,processing,rejected,completed,cancelled',
            'items' => 'required|array|min:1',
            'items.*.buffet_item_id' => 'required|exists:buffet_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string',

        ];
    }
}
