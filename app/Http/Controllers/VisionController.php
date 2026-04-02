<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Requests\VisionRequest;
use App\Http\Resources\VisionResource;
use App\Models\Tenant\Vision;
use Illuminate\Http\JsonResponse;

class VisionController extends Controller
{
    public function index()
    {
        $vision = Vision::latest()->first();

        if (!$vision) {
            return response()->json(['message' => trans('crud.not_found')], 404);
        }

        return response()->json(['data' => new VisionResource($vision)], 200);
    }

    public function store(VisionRequest $request): JsonResponse
    {
        $vision = Vision::create($request->validated());

        return response()->json([
            'data' => new VisionResource($vision),
            'message' => trans('crud.created'),
        ], 200);
    }

    public function show(string $id): JsonResponse
    {
        $vision = Vision::findOrFail($id);

        return response()->json(['data' => new VisionResource($vision)], 200);
    }

    public function update(VisionRequest $request, string $id): JsonResponse
    {
        $vision = Vision::findOrFail($id);

        $vision->update($request->validated());

        return response()->json([
            'data' => new VisionResource($vision),
            'message' => trans('crud.updated'),
        ]);
    }

    public function destroy(DeleteRequest $request): JsonResponse
    {
        $ids = $request->input('ids');

        try {
            Vision::whereIn('id', $ids)->delete();
        } catch (\Exception $exception) {
            return response()->json(['message' => trans('crud.delete.error')], 400);
        }

        return response()->json(['message' => trans('crud.deleted')], 200);
    }
}
