<?php

namespace App\Http\Controllers;

use App\Models\Tenant\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectUserController extends Controller
{
    public function store(Request $request, string $id): JsonResponse
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['exists:users,id'],
        ]);

        $project->users()->sync($validated['user_ids']);

        return response()->json([
            'message' => trans('crud.created'),
            'data' => $project->load('users'),
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['exists:users,id'],
        ]);

        $project = Project::findOrFail($id);

        $project->users()->sync($validated['user_ids']);

        return response()->json([
            'message' => trans('crud.updated'),
            'data' => $project->load('users'),
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:users,id'],
        ]);

        $project = Project::findOrFail($id);

        $project->users()->detach($validated['ids']);

        return response()->json([
            'message' => trans('crud.deleted'),
        ]);
    }
}
