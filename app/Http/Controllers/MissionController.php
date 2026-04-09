<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Requests\MissionRequest;
use App\Http\Resources\MissionResource;
use App\Models\Tenant\Mission;
use App\Services\QueryBuilderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MissionController extends Controller
{
    public function __construct(private QueryBuilderService $queryBuilder) {}

    public function index(Request $request): JsonResponse
    {
        $missions = Mission::query()->with([
            'approvedBy',
            'createdBy',
            'assignees',
        ]);

        $result = $this->queryBuilder->applyQuery($request, $missions);

        return response()->json([
            'data' => MissionResource::collection($result),
        ]);
    }

    public function store(MissionRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $assigneeIds = $validated['assignee_ids'] ?? [];
        unset($validated['assignee_ids']);

        $mission = Mission::create($validated);
        $mission->assignees()->sync($assigneeIds);

        $mission->load([
            'approvedBy',
            'createdBy',
            'assignees',
        ]);

        return response()->json([
            'message' => trans('crud.created'),
            'data' => new MissionResource($mission),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $mission = Mission::with([
            'approvedBy',
            'createdBy',
            'assignees',
        ])->findOrFail($id);

        return response()->json([
            'data' => new MissionResource($mission),
        ]);
    }

    public function update(MissionRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();

        $assigneeIds = $validated['assignee_ids'] ?? [];
        unset($validated['assignee_ids']);

        $mission = Mission::findOrFail($id);
        $mission->update($validated);
        $mission->assignees()->sync($assigneeIds);

        $mission->load([
            'approvedBy',
            'createdBy',
            'assignees',
        ]);

        return response()->json([
            'message' => trans('crud.updated'),
            'data' => new MissionResource($mission),
        ]);
    }

    public function destroy(DeleteRequest $request): JsonResponse
    {
        Mission::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => trans('crud.deleted'),
        ]);
    }
}
