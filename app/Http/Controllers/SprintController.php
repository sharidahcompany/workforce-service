<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Requests\SprintRequest;
use App\Http\Resources\SprintResource;
use App\Models\Tenant\Sprint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SprintController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Sprint::with([
            'project',
            'stages',
            'stages.tasks',
            'stages.tasks.assignedBy',
            'stages.tasks.sprintStage',
        ])->orderByDesc('id');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $sprints = $query->get();

        return response()->json([
            'data' => SprintResource::collection($sprints),
        ], 200);
    }

    public function store(SprintRequest $request): JsonResponse
    {
        $sprint = Sprint::create($request->validated());

        $sprint->load([
            'project',
            'stages',
            'stages.tasks',
            'stages.tasks.assignedBy',
            'stages.tasks.sprintStage',
        ]);

        return response()->json([
            'data' => new SprintResource($sprint),
            'message' => trans('crud.created'),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $sprint = Sprint::with([
            'project',
            'stages',
            'stages.tasks',
            'stages.tasks.assignedBy',
            'stages.tasks.sprintStage',
        ])->findOrFail($id);

        return response()->json([
            'data' => new SprintResource($sprint),
        ], 200);
    }

    public function update(SprintRequest $request, string $id): JsonResponse
    {
        $sprint = Sprint::findOrFail($id);

        $sprint->update($request->validated());

        $sprint->load([
            'project',
            'stages',
            'stages.tasks',
            'stages.tasks.assignedBy',
            'stages.tasks.sprintStage',
        ]);

        return response()->json([
            'data' => new SprintResource($sprint),
            'message' => trans('crud.updated'),
        ], 200);
    }

    public function destroy(DeleteRequest $request): JsonResponse
    {
        $ids = $request->input('ids');

        Sprint::whereIn('id', $ids)->delete();

        return response()->json([
            'message' => trans('crud.deleted'),
        ], 200);
    }
}
