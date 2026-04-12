<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ScholarshipRequestRequest;
use App\Http\Resources\ScholarshipRequestResource;
use App\Models\Tenant\ScholarshipRequest;
use App\Services\QueryBuilderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScholarshipRequestController extends Controller
{
    public function __construct(private QueryBuilderService $queryBuilder) {}

    public function index(Request $request): JsonResponse
    {
        $query = ScholarshipRequest::query()->with([
            'user',
            'scholarship',
        ]);

        $result = $this->queryBuilder->applyQuery($request, $query);

        return response()->json([
            'data' => ScholarshipRequestResource::collection($result),
        ]);
    }

    public function store(ScholarshipRequestRequest $request): JsonResponse
    {
        $scholarshipRequest = ScholarshipRequest::create($request->validated());

        $scholarshipRequest->load([
            'user',
            'scholarship',
        ]);

        return response()->json([
            'message' => trans('crud.created'),
            'data' => new ScholarshipRequestResource($scholarshipRequest),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $scholarshipRequest = ScholarshipRequest::with([
            'user',
            'scholarship',
        ])->findOrFail($id);

        return response()->json([
            'data' => new ScholarshipRequestResource($scholarshipRequest),
        ]);
    }

    public function update(ScholarshipRequestRequest $request, string $id): JsonResponse
    {
        $scholarshipRequest = ScholarshipRequest::findOrFail($id);
        $scholarshipRequest->update($request->validated());

        $scholarshipRequest->load([
            'user',
            'scholarship',
        ]);

        return response()->json([
            'message' => trans('crud.updated'),
            'data' => new ScholarshipRequestResource($scholarshipRequest),
        ]);
    }

    public function destroy(DeleteRequest $request): JsonResponse
    {
        ScholarshipRequest::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => trans('crud.deleted'),
        ]);
    }
}
