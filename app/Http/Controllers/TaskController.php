<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Tenant\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\MoveTaskRequest;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $tasks = Task::with([
            'sprintStage',
            'assignedBy',
            'users',
        ])->orderBy('order')->get();

        return response()->json([
            'data' => TaskResource::collection($tasks),
        ], 200);
    }

    public function store(TaskRequest $request): JsonResponse
    {
        $task = Task::create($request->validated());

        if ($request->has('users')) {
            $task->users()->sync($request->users);
        }

        $task->load(['sprintStage', 'assignedBy']);

        return response()->json([
            'data' => new TaskResource($task),
            'message' => trans('crud.created'),
        ], 200);
    }

    public function show(string $id): JsonResponse
    {
        $task = Task::with([
            'sprintStage',
            'assignedBy',
        ])->findOrFail($id);

        return response()->json([
            'data' => new TaskResource($task),
        ], 200);
    }

    public function update(TaskRequest $request, string $id): JsonResponse
    {
        $task = Task::findOrFail($id);

        $validated = $request->validated();

        $task->update($validated);

        if ($request->has('users')) {
            $task->users()->sync($validated['users'] ?? []);
        }

        $task->load([
            'sprintStage',
            'assignedBy',
            'users',
        ]);

        return response()->json([
            'data' => new TaskResource($task),
            'message' => trans('crud.updated'),
        ], 200);
    }

    public function destroy(DeleteRequest $request): JsonResponse
    {
        $ids = $request->input('ids');

        Task::whereIn('id', $ids)->delete();

        return response()->json([
            'message' => trans('crud.deleted'),
        ], 200);
    }

    public function move(MoveTaskRequest $request, string $task): JsonResponse
    {
        $validated = $request->validated();
        $task = Task::findOrFail($task);

        DB::transaction(function () use ($task, $validated) {
            $fromStageId = (int) $task->sprint_stage_id;
            $toStageId = (int) $validated['sprint_stage_id'];
            $newOrder = (int) $validated['order'];
            $oldOrder = (int) $task->order;

            if ($fromStageId === $toStageId) {
                if ($newOrder === $oldOrder) {
                    return;
                }

                $maxOrder = Task::where('sprint_stage_id', $fromStageId)->count();
                $newOrder = max(1, min($newOrder, $maxOrder));

                if ($newOrder < $oldOrder) {
                    Task::where('sprint_stage_id', $fromStageId)
                        ->where('id', '!=', $task->id)
                        ->where('order', '>=', $newOrder)
                        ->where('order', '<', $oldOrder)
                        ->increment('order');
                } else {
                    Task::where('sprint_stage_id', $fromStageId)
                        ->where('id', '!=', $task->id)
                        ->where('order', '>', $oldOrder)
                        ->where('order', '<=', $newOrder)
                        ->decrement('order');
                }

                $task->update([
                    'order' => $newOrder,
                ]);

                $task->save();

                return;
            }

            $toStageCount = Task::where('sprint_stage_id', $toStageId)->count();
            $newOrder = max(1, min($newOrder, $toStageCount + 1));

            Task::where('sprint_stage_id', $fromStageId)
                ->where('id', '!=', $task->id)
                ->where('order', '>', $oldOrder)
                ->decrement('order');

            Task::where('sprint_stage_id', $toStageId)
                ->where('order', '>=', $newOrder)
                ->increment('order');

            $task->update([
                'sprint_stage_id' => $toStageId,
                'order' => $newOrder,
            ]);
        });

        return response()->json([
            'message' => trans('crud.updated'),
        ]);
    }
}
