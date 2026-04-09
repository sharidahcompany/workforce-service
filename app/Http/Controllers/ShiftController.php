<?php

namespace App\Http\Controllers;

use App\Enums\WeekDay;
use App\Http\Requests\AssignShiftUsersRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ShiftRequest;
use App\Http\Resources\ShiftResource;
use App\Models\Tenant\Shift;
use App\Services\QueryBuilderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function __construct(private QueryBuilderService $queryBuilder) {}

    public function index(Request $request): JsonResponse
    {
        $shifts = Shift::query();

        $result = $this->queryBuilder->applyQuery($request, $shifts);

        return response()->json([
            'data' => ShiftResource::collection($result),
        ]);
    }

    public function store(ShiftRequest $request): JsonResponse
    {
        $shift = Shift::create($request->validated());

        return response()->json([
            'message' => trans('crud.created'),
            'data' => new ShiftResource($shift),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $shift = Shift::with('users')->findOrFail($id);

        return response()->json([
            'data' => new ShiftResource($shift),
        ]);
    }

    public function update(ShiftRequest $request, string $id): JsonResponse
    {
        $shift = Shift::findOrFail($id);
        $shift->update($request->validated());

        return response()->json([
            'message' => trans('crud.updated'),
            'data' => new ShiftResource($shift),
        ]);
    }

    public function destroy(DeleteRequest $request): JsonResponse
    {
        Shift::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => trans('crud.deleted'),
        ]);
    }

    public function weekDays(): JsonResponse
    {
        return response()->json([
            'data' => WeekDay::options(),
        ]);
    }

    public function assignUsers(AssignShiftUsersRequest $request, string $id): JsonResponse
    {
        $shift = Shift::findOrFail($id);

        $shift->users()->syncWithoutDetaching($request->validated()['user_ids']);
        $shift->load('users');

        return response()->json([
            'message' => trans('crud.updated'),
            'data' => new ShiftResource($shift),
        ]);
    }

    public function syncUsers(AssignShiftUsersRequest $request, string $id): JsonResponse
    {
        $shift = Shift::findOrFail($id);

        $shift->users()->sync($request->validated()['user_ids']);
        $shift->load('users');

        return response()->json([
            'message' => trans('crud.updated'),
            'data' => new ShiftResource($shift),
        ]);
    }

    public function removeUsers(AssignShiftUsersRequest $request, string $id): JsonResponse
    {
        $shift = Shift::findOrFail($id);

        $shift->users()->detach($request->validated()['user_ids']);
        $shift->load('users');

        return response()->json([
            'message' => trans('crud.updated'),
            'data' => new ShiftResource($shift),
        ]);
    }
}
