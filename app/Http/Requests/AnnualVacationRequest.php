<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnualVacationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */ public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $currentYear = now()->year + 10;

        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'year' => ['required', 'integer', 'min:2000', 'max:' . $currentYear],
            'balance' => ['required', 'integer', 'min:0'],
            'used' => ['nullable', 'integer', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $balance = (int) ($this->balance ?? 0);
        $used = (int) ($this->used ?? 0);

        $this->merge([
            'used' => $used,
            'remaining' => max($balance - $used, 0),
        ]);
    }
}
