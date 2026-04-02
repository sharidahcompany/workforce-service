<?php

namespace App\Http\Requests;

use App\Models\Tenant\Department;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $departmentId = $this->route('department')?->id ?? $this->route('department');
        $isPost = $this->method() === 'POST';

        return [
            'name' => [$isPost ? 'required' : 'sometimes', 'array'],
            'name.ar' => [$isPost ? 'required' : 'sometimes', 'string', 'max:255'],
            'name.en' => [$isPost ? 'required' : 'sometimes', 'string', 'max:255'],

            'branch_id' => [$isPost ? 'required' : 'sometimes', 'exists:branches,id'],

            'parent_id' => [
                'nullable',
                'exists:departments,id',
                Rule::notIn([$departmentId]),
            ],

            'manager_id' => ['nullable', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'parent_id.not_in' => 'القسم لا يمكن أن يكون تابعًا لنفسه.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $departmentRouteParam = $this->route('department');
            $departmentId = is_object($departmentRouteParam)
                ? $departmentRouteParam->id
                : (int) $departmentRouteParam;

            $parentId = $this->input('parent_id');

            if (! $departmentId) {
                return;
            }

            $department = Department::query()->find($departmentId);

            if (! $department) {
                return;
            }

            if (is_null($department->parent_id) && ! empty($parentId)) {
                $validator->errors()->add(
                    'parent_id',
                    'لا يمكن تحويل القسم الرئيسي إلى قسم فرعي.'
                );

                return;
            }

            if (empty($parentId)) {
                return;
            }

            if ($this->createsCycle($departmentId, (int) $parentId)) {
                $validator->errors()->add(
                    'parent_id',
                    'لا يمكن اختيار قسم فرعي كقسم أب، لأن هذا يسبب حلقة في الهيكل التنظيمي.'
                );
            }
        });
    }

    private function createsCycle(int $departmentId, int $parentId): bool
    {
        while ($parentId) {
            if ($parentId === $departmentId) {
                return true;
            }

            $parent = Department::query()
                ->select('id', 'parent_id')
                ->find($parentId);

            if (! $parent) {
                return false;
            }

            $parentId = (int) $parent->parent_id;
        }

        return false;
    }
}
