<?php

namespace App\Http\Controllers;

use App\Http\Requests\BranchRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Resources\BranchResource;
use App\Models\Tenant\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $branch = Branch::with(['manager', 'departments', 'departments.manager', 'departments.children', 'departments.childrenRecursive', 'departments.branch', 'departments.parent', 'departments.users'])->get();

        return response()->json(['data' =>   BranchResource::collection($branch)], 200);
    }

    public function store(BranchRequest $request): JsonResponse
    {

        $branch = Branch::create($request->validated());

        return response()->json([
            'data' => new BranchResource($branch),
            'message' => trans('crud.created'),
        ], 200);
    }

    public function show(string $id): JsonResponse
    {
        $branch = Branch::findOrFail($id);

        return response()->json(['data' => new BranchResource($branch)], 200);
    }

    public function update(BranchRequest $request, string $id): JsonResponse
    {
        $branch = Branch::findOrFail($id);

        $branch->update($request->validated());

        return response()->json([
            'data' => new BranchResource($branch),
            'message' => trans('crud.updated'),
        ]);
    }

    public function destroy(DeleteRequest $request): JsonResponse
    {
        $ids = $request->input('ids', []);

        if (collect($ids)->contains(fn($id) => (int) $id === 1)) {
            return response()->json([
                'message' => trans('crud.delete.hq.error'),
            ], 422);
        }

        try {
            Branch::whereIn('id', $ids)->delete();
        } catch (\Throwable $exception) {
            return response()->json([
                'message' => trans('crud.delete.error'),
            ], 400);
        }

        return response()->json([
            'message' => trans('crud.deleted'),
        ], 200);
    }
}
