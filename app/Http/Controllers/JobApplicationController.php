<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Requests\JobApplicationRequest;
use App\Http\Resources\JobApplicationResource;
use App\Models\Tenant\JobApplication;
use App\Services\QueryBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class JobApplicationController extends Controller
{
    public function __construct(private QueryBuilderService $queryBuilder) {}

    public function index(Request $request): JsonResponse
    {
        $jobApplications = JobApplication::query()->with([
            'jobPost',
            'user',

        ]);

        $result = $this->queryBuilder->applyQuery($request, $jobApplications);

        return response()->json([
            'data' => JobApplicationResource::collection($result),
        ]);
    }

    public function store(JobApplicationRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $jobApplication = JobApplication::create($data);
        $jobApplication->load(['jobPost', 'user', 'experiences']);

        return response()->json([
            'message' => trans('crud.created'),
            'data' => new JobApplicationResource($jobApplication),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $jobApplication = JobApplication::with([
            'jobPost',
            'user',
            'experiences',
            'interviews',
        ])->findOrFail($id);

        return response()->json([
            'data' => new JobApplicationResource($jobApplication),
        ]);
    }

    public function update(JobApplicationRequest $request, string $id): JsonResponse
    {
        $jobApplication = JobApplication::findOrFail($id);
        $jobApplication->update($request->validated());
        $jobApplication->load(['jobPost', 'user', 'experiences']);

        return response()->json([
            'message' => trans('crud.updated'),
            'data' => new JobApplicationResource($jobApplication),
        ]);
    }

    public function destroy(DeleteRequest $request): JsonResponse
    {
        JobApplication::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => trans('crud.deleted'),
        ]);
    }
}
