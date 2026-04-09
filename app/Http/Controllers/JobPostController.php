<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Requests\JobPostRequest;
use App\Http\Resources\JobPostResource;
use App\Models\Tenant\JobPost;
use App\Services\QueryBuilderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobPostController extends Controller
{
    public function __construct(private QueryBuilderService $queryBuilder) {}

    public function index(Request $request): JsonResponse
    {
        $jobPosts = JobPost::query();

        $result = $this->queryBuilder->applyQuery($request, $jobPosts);

        return response()->json([
            'data' => JobPostResource::collection($result),
        ]);
    }

    public function store(JobPostRequest $request): JsonResponse
    {
        $jobPost = JobPost::create($request->validated());

        return response()->json([
            'message' => trans('crud.created'),
            'data' => new JobPostResource($jobPost),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $jobPost = JobPost::findOrFail($id);

        return response()->json([
            'data' => new JobPostResource($jobPost),
        ]);
    }

    public function update(JobPostRequest $request, string $id): JsonResponse
    {
        $jobPost = JobPost::findOrFail($id);
        $jobPost->update($request->validated());

        return response()->json([
            'message' => trans('crud.updated'),
            'data' => new JobPostResource($jobPost),
        ]);
    }

    public function destroy(DeleteRequest $request): JsonResponse
    {
        $ids = $request->input('ids', []);

        JobPost::whereIn('id', $ids)->delete();

        return response()->json([
            'message' => trans('crud.deleted'),
        ]);
    }
}
