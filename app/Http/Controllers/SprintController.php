<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Requests\SprintRequest;
use App\Http\Resources\SprintResource;
use App\Models\Tenant\Sprint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ReorderSprintStagesRequest;
use App\Models\Tenant\SprintStage;
use Illuminate\Support\Facades\DB;


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

        $sprint->stages()->createMany(
            [
                [
                    'order' => 1,
                    'name' => 'قيد الانتظار',
                ],
                [
                    'order' => 2,
                    'name' => 'قيد التنفيذ',
                ],
                [
                    'order' => 3,
                    'name' => 'قيد المراجعة',
                ],
                [
                    'order' => 4,
                    'name' => 'مكتملة',
                ],
            ]
        );

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

    public function reorder(ReorderSprintStagesRequest $request, string $sprint): JsonResponse
    {

        $validated = $request->validated();

        $sprint = Sprint::findOrFail($sprint);

        DB::transaction(function () use ($validated, $sprint) {
            $stageIds = collect($validated['stages'])->pluck('id');

            $existingStageIds = SprintStage::where('sprint_id', $sprint->id)
                ->whereIn('id', $stageIds)
                ->pluck('id');

            if ($existingStageIds->count() !== $stageIds->count()) {
                abort(422, 'Some stages do not belong to this sprint.');
            }

            foreach ($validated['stages'] as $stageData) {
                SprintStage::where('id', $stageData['id'])
                    ->where('sprint_id', $sprint->id)
                    ->update([
                        'order' => $stageData['order'],
                    ]);
            }
        });

        return response()->json([
            'message' => trans('crud.updated'),
        ]);
    }
}
