<?php

namespace App\Http\Requests;

use App\Enums\AttendanceStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttendanceRequest extends FormRequest
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
        if ($this->isMethod('post')) {
            return [
                'user_id' => ['required', 'exists:users,id'],
                'shift_id' => ['required', 'exists:shifts,id'],

                'attendance_date' => ['required', 'date'],

                'shift_start_at' => ['required', 'date'],
                'shift_end_at' => ['required', 'date', 'after:shift_start_at'],

                'check_in' => ['nullable', 'date'],
                'check_out' => ['nullable', 'date', 'after_or_equal:check_in'],

                'late_minutes' => ['nullable', 'integer', 'min:0'],
                'overtime_minutes' => ['nullable', 'integer', 'min:0'],

                'status' => ['nullable', Rule::in(AttendanceStatus::values())],
            ];
        }

        return [
            'user_id' => ['sometimes', 'exists:users,id'],
            'shift_id' => ['sometimes', 'exists:shifts,id'],

            'attendance_date' => ['sometimes', 'date'],

            'shift_start_at' => ['sometimes', 'date'],
            'shift_end_at' => ['sometimes', 'date'],

            'check_in' => ['sometimes', 'nullable', 'date'],
            'check_out' => ['sometimes', 'nullable', 'date'],

            'late_minutes' => ['sometimes', 'integer', 'min:0'],
            'overtime_minutes' => ['sometimes', 'integer', 'min:0'],

            'status' => ['sometimes', Rule::in(AttendanceStatus::values())],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $shiftStartAt = $this->input('shift_start_at');
            $shiftEndAt = $this->input('shift_end_at');
            $checkIn = $this->input('check_in');
            $checkOut = $this->input('check_out');

            if ($shiftStartAt && $shiftEndAt && strtotime($shiftEndAt) <= strtotime($shiftStartAt)) {
                $validator->errors()->add('shift_end_at', trans('validation.after', [
                    'attribute' => 'shift end at',
                    'date' => 'shift start at',
                ]));
            }

            if ($checkIn && $checkOut && strtotime($checkOut) < strtotime($checkIn)) {
                $validator->errors()->add('check_out', trans('validation.after_or_equal', [
                    'attribute' => 'check out',
                    'date' => 'check in',
                ]));
            }
        });
    }
}
