<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Requests\CareerRequest;
use App\Http\Resources\CareerResource;
use App\Models\Tenant\Career;

class CareerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $careers = Career::all();

        return response()->json(['data' => CareerResource::collection($careers)]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CareerRequest $request)
    {
        
        $validated = $request->validated();

        $career = Career::create($validated);
        if ($request->hasFile('cover')) {
            $career->addMediaFromRequest('cover')->toMediaCollection('career');
        }
        if ($request->has('benefits')) {
            $career->benefits()->sync($request->benefits);
        }

        return response()->json([
            'data' => new CareerResource($career),
            'message' => trans('crud.created'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $career = Career::findOrFail($id);

        return response()->json(['data' => new CareerResource($career)], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CareerRequest $request, string $id)
    {
        $career = Career::findOrFail($id);

        $career->update($request->validated());
        if ($request->hasFile('cover')) {
            $career->clearMediaCollection('cover');
            $career->addMediaFromRequest('cover')->toMediaCollection('career');
        }
        if ($request->has('benefits')) {
            $career->benefits()->sync($request->benefits);
        }
        return response()->json([
            'data' => new CareerResource($career),
            'message' => trans('crud.updated'),
        ]);
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
            Career::whereIn('id', $ids)->delete();
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
