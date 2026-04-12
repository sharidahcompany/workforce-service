<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Resources\ScholarshipResource;
use App\Models\Tenant\Scholarship;
use App\Models\Tenant\ScholarshipRequest;
use App\Services\QueryBuilderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    public function __construct(private QueryBuilderService $queryBuilder) {}

    public function index(Request $request): JsonResponse
    {
        $scholarships = Scholarship::query();

        $result = $this->queryBuilder->applyQuery($request, $scholarships);

        return response()->json([
            'data' => ScholarshipResource::collection($result),
        ]);
    }

    public function store(ScholarshipRequest $request): JsonResponse
    {
        $scholarship = Scholarship::create([
            ...$request->validated(),
            'is_active' => $request->validated()['is_active'] ?? true,
        ]);

        return response()->json([
            'message' => trans('crud.created'),
            'data' => new ScholarshipResource($scholarship),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $scholarship = Scholarship::findOrFail($id);

        return response()->json([
            'data' => new ScholarshipResource($scholarship),
        ]);
    }

    public function update(ScholarshipRequest $request, string $id): JsonResponse
    {
        $scholarship = Scholarship::findOrFail($id);
        $scholarship->update($request->validated());

        return response()->json([
            'message' => trans('crud.updated'),
            'data' => new ScholarshipResource($scholarship),
        ]);
    }

    public function destroy(DeleteRequest $request): JsonResponse
    {
        Scholarship::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => trans('crud.deleted'),
        ]);
    }
}
