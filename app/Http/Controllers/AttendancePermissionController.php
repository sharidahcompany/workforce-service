<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendancePermissionRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Resources\AttendancePermissionResource;
use App\Models\Tenant\AttendancePermission;
use App\Services\QueryBuilderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendancePermissionController extends Controller
{
    public function __construct(private QueryBuilderService $queryBuilder) {}

    public function index(Request $request): JsonResponse
    {
        $attendancePermissions = AttendancePermission::query()->with([
            'user',
            'approvedBy',
        ]);

        $result = $this->queryBuilder->applyQuery($request, $attendancePermissions);

        return response()->json([
            'data' => AttendancePermissionResource::collection($result),
        ]);
    }

    public function store(AttendancePermissionRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $validated['user_id'] = Auth::id();

        $attendancePermission = AttendancePermission::create($validated);

        $attendancePermission->load([
            'user',
            'approvedBy',
        ]);

        return response()->json([
            'message' => trans('crud.created'),
            'data' => new AttendancePermissionResource($attendancePermission),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $attendancePermission = AttendancePermission::with([
            'user',
            'approvedBy',
        ])->findOrFail($id);

        return response()->json([
            'data' => new AttendancePermissionResource($attendancePermission),
        ]);
    }

    public function update(AttendancePermissionRequest $request, string $id): JsonResponse
    {
        $attendancePermission = AttendancePermission::findOrFail($id);

        $attendancePermission->update($request->validated());

        $attendancePermission->load([
            'user',
            'approvedBy',
        ]);

        return response()->json([
            'message' => trans('crud.updated'),
            'data' => new AttendancePermissionResource($attendancePermission),
        ]);
    }

    public function destroy(DeleteRequest $request): JsonResponse
    {
        AttendancePermission::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => trans('crud.deleted'),
        ]);
    }
}
