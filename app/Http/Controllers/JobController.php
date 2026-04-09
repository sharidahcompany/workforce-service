<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Requests\JobRequest;
use App\Http\Resources\JobResource;
use App\Models\Tenant\Job;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $job = Job::all();

        return response()->json(['data' => JobResource::collection($job)]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobRequest $request)
    {
        $validated = $request->validated();

        $job = Job::create($validated);

        return response()->json([
            'data' => new JobResource($job),
            'message' => trans('crud.created'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $job = Job::findOrFail($id);

        return response()->json(['data' => new JobResource($job)], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(jobRequest $request, string $id)
    {
        $job = Job::findOrFail($id);

        $job->update($request->validated());

        return response()->json([
            'data' => new JobResource($job),
            'message' => trans('crud.updated'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteRequest $request)
    {
        $ids = $request->input('ids', []);

        if (collect($ids)->contains(fn($id) => (int) $id === 1)) {
            return response()->json([
                'message' => trans('crud.delete.hq.error'),
            ], 422);
        }

        try {
            Job::whereIn('id', $ids)->delete();
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
