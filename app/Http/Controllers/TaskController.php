<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Tenant\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $tasks = Task::with([
            'sprintStage',
            'assignedBy',
        ])->orderBy('order')->get();

        return response()->json([
            'data' => TaskResource::collection($tasks),
        ], 200);
    }

    public function store(TaskRequest $request): JsonResponse
    {
        $task = Task::create($request->validated());
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
        $task->update($request->validated());
        $task->load(['sprintStage', 'assignedBy']);

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
}
