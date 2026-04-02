<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Requests\GoalRequest;
use App\Http\Resources\GoalResource;
use App\Models\Tenant\Goal;
use Illuminate\Http\JsonResponse;

class GoalController extends Controller
{
    public function index()
    {
        $goal = Goal::all();

        return response()->json(['data' => GoalResource::collection($goal)], 200);
    }

    public function store(GoalRequest $request): JsonResponse
    {
        $goal = Goal::create($request->validated());

        return response()->json([
            'data' => new GoalResource($goal),
            'message' => trans('crud.created'),
        ], 200);
    }

    public function show(string $id): JsonResponse
    {
        $goal = Goal::findOrFail($id);

        return response()->json(['data' => new GoalResource($goal)], 200);
    }

    public function update(GoalRequest $request, string $id): JsonResponse
    {
        $goal = Goal::findOrFail($id);

        $goal->update($request->validated());

        return response()->json([
            'data' => new GoalResource($goal),
            'message' => trans('crud.updated'),
        ]);
    }

    public function destroy(DeleteRequest $request): JsonResponse
    {
        $ids = $request->input('ids');

        try {
            Goal::whereIn('id', $ids)->delete();
        } catch (\Exception $exception) {
            return response()->json(['message' => trans('crud.delete.error')], 400);
        }

        return response()->json(['message' => trans('crud.deleted')], 200);
    }
}
