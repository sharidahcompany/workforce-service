<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Requests\SprintStageRequest;
use App\Http\Resources\SprintStageResource;
use App\Models\Tenant\SprintStage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SprintStageController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $sprintStages = SprintStage::with([
            'sprint',
            'tasks',
            'tasks.assignedBy',
            'tasks.sprintStage',
        ])
            ->orderBy('order')
            ->get();

        return response()->json([
            'data' => SprintStageResource::collection($sprintStages),
        ], 200);
    }

    public function store(SprintStageRequest $request): JsonResponse
    {
        $sprintStage = SprintStage::create($request->validated());

        $sprintStage->load([
            'sprint',
            'tasks',
            'tasks.assignedBy',
            'tasks.sprintStage',
        ]);

        return response()->json([
            'data' => new SprintStageResource($sprintStage),
            'message' => trans('crud.created'),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $sprintStage = SprintStage::with([
            'sprint',
            'tasks',
            'tasks.assignedBy',
            'tasks.sprintStage',
        ])->findOrFail($id);

        return response()->json([
            'data' => new SprintStageResource($sprintStage),
        ], 200);
    }

    public function update(SprintStageRequest $request, string $id): JsonResponse
    {
        $sprintStage = SprintStage::findOrFail($id);

        $sprintStage->update($request->validated());

        $sprintStage->load([
            'sprint',
            'tasks',
            'tasks.assignedBy',
            'tasks.sprintStage',
        ]);

        return response()->json([
            'data' => new SprintStageResource($sprintStage),
            'message' => trans('crud.updated'),
        ], 200);
    }

    public function destroy(DeleteRequest $request): JsonResponse
    {
        $ids = $request->input('ids');

        SprintStage::whereIn('id', $ids)->delete();

        return response()->json([
            'message' => trans('crud.deleted'),
        ], 200);
    }
}
