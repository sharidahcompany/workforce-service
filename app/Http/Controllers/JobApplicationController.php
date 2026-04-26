<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Requests\JobApplicationRecommendedByRequest;
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
            'recommendeder',
            'careerPost',
            'user',

        ]);

        $result = $this->queryBuilder->applyQuery($request, $jobApplications);

        return response()->json([
            'data' => JobApplicationResource::collection($result),
        ]);
    }

    public function store(JobApplicationRequest $request)
    {
        if ($request->hasFile('file')) {
            return 'gfd';
            $jobApplication->addMediaFromRequest('file')->toMediaCollection('cv');
        }
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $jobApplication = JobApplication::create($data);
        $jobApplication->load(['careerPost', 'user']);

      

        return response()->json([
            'message' => trans('crud.created'),
            'data' => new JobApplicationResource($jobApplication),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $jobApplication = JobApplication::with([
            'recommendeder',
            'careerPost',
            'user',
        ])->findOrFail($id);

        return response()->json([
            'data' => new JobApplicationResource($jobApplication),
        ]);
    }

    public function update(JobApplicationRequest $request, string $id): JsonResponse
    {
        $jobApplication = JobApplication::findOrFail($id);
        $jobApplication->update($request->validated());
        $jobApplication->load(['recommendeder','careerPost', 'user']);

        if ($request->hasFile('file')) {
            $jobApplication->addMediaFromRequest('file')->toMediaCollection('cv');
        }

        return response()->json([
            'message' => trans('crud.updated'),
            'data' => new JobApplicationResource($jobApplication),
        ]);
    }

    public function destroy(DeleteRequest $request): JsonResponse
    {
        $jobApplications = JobApplication::whereIn('id', $request->ids)->get();

        foreach ($jobApplications as $jobApplication) {
            $jobApplication->delete();
        }

        return response()->json([
            'message' => trans('crud.deleted'),
        ]);
    }

    public function jobApplicationAccepted($id){
        $jobApplication = JobApplication::find($id);
        $jobApplication->update([
            'accepted_by'=>Auth::id(),
            'status'=>'waiting',
        ]);
        return response()->json([
            'message' => trans('crud.request_sent'),
            'data' => new JobApplicationResource($jobApplication),
        ]);
    }
    public function jobApplicationConfirmed($id){
        $jobApplication = JobApplication::find($id);
        $jobApplication->update([
            'accepted_by'=>Auth::id(),
            'status'=>'accepted',
        ]);
        return response()->json([
            'message' => trans('crud.accepted'),
            'data' => new JobApplicationResource($jobApplication),
        ]);
    }
    
}
