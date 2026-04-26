<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Requests\JobInterviewRequest;
use App\Http\Requests\JobInterviewRescheduleRequest;
use App\Http\Resources\JobInterviewResource;
use App\Models\Tenant\JobApplication;
use App\Models\Tenant\JobInterview;
use App\Services\QueryBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class JobInterviewController extends Controller
{
    public function __construct(private QueryBuilderService $queryBuilder) {}

    public function index(Request $request): JsonResponse
    {
        $interviews = JobInterview::query()->with([
            'children',
            'user',
            'interviewer',
        ]);

        $result = $this->queryBuilder->applyQuery($request, $interviews);

        return response()->json([
            'data' => JobInterviewResource::collection($result),
        ]);
    }

    public function store(JobInterviewRequest $request): JsonResponse
    {
        $interview = JobInterview::create($request->validated());
        $interview->load([
            'children',
            'user',
            'interviewer',
        ]);

        return response()->json([
            'message' => trans('crud.created'),
            'data' => new JobInterviewResource($interview),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $interview = JobInterview::with([
            'children',
            'user',
            'interviewer',
        ])->findOrFail($id);

        return response()->json([
            'data' => new JobInterviewResource($interview),
        ]);
    }

    public function update(JobInterviewRequest $request, string $id): JsonResponse
    {
        $interview = JobInterview::findOrFail($id);
        $interview->update($request->validated());
        $interview->load([
            'children',
            'user',
            'interviewer',
        ]);

        return response()->json([
            'message' => trans('crud.updated'),
            'data' => new JobInterviewResource($interview),
        ]);
    }

    public function interviewReschedule(JobInterviewRescheduleRequest $request,$id){

        $findInterview = JobInterview::findOrFail($id);
        $data = $findInterview->toArray();
        unset($data['id'], $data['created_at'], $data['updated_at']);

        $data['parent_id'] = $findInterview->id;
        $data['scheduled_at'] = $request->validated('scheduled_at');
        $data['reschedule_reason'] = $request->validated('reschedule_reason');
        $data['status'] = 'rescheduled'; 

        $interview = JobInterview::create($data);
        return response()->json([
            'message' => trans('crud.reschedule'),
            'data' => new JobInterviewResource($interview),
        ]);
    }
    public function destroy(DeleteRequest $request): JsonResponse
    {
        JobInterview::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => trans('crud.deleted'),
        ]);
    }

    

   
}
