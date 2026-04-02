<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Requests\DepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Tenant\Department;
use Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with([
            'branch',
            'manager',
            'parent',
            'childrenRecursive',
            'children',
            'users'
        ])
            ->get();

        return response()->json([
            'data' => DepartmentResource::collection($departments),
        ], 200);
    }

    public function store(DepartmentRequest $request): JsonResponse
    {
        $department = Department::create($request->validated());

        return response()->json([
            'data' => new DepartmentResource($department),
            'message' => trans('crud.created'),
        ], 200);
    }

    public function show(string $id): JsonResponse
    {
        $department = Department::with([
            'branch',
            'manager',
            'parent',
            'childrenRecursive',
            'children',
        ])->findOrFail($id);

        return response()->json(['data' => new DepartmentResource($department)], 200);
    }

    public function update(DepartmentRequest $request, string $id): JsonResponse
    {
        $department = Department::findOrFail($id);

        $department->update($request->validated());

        return response()->json([
            'data' => new DepartmentResource($department),
            'message' => trans('crud.updated'),
        ]);
    }

    public function destroy(DeleteRequest $request): JsonResponse
    {
        $ids = $request->input('ids');

        $departments = Department::query()
            ->withCount('children')
            ->whereIn('id', $ids)
            ->get();

        $departmentWithChildren = $departments->firstWhere('children_count', '>', 0);

        if ($departmentWithChildren) {
            $name = $departmentWithChildren->name[app()->getLocale()]
                ?? $departmentWithChildren->name['ar']
                ?? 'هذا القسم';

            return response()->json([
                'message' =>  trans('validation.department_has_children'),
            ], 422);
        }

        try {
            Department::whereIn('id', $ids)->delete();
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return response()->json(['message' => trans('crud.deleted')], 200);
    }
}
