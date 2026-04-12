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
        $Career = Career::all();

        return response()->json(['data' => CareerResource::collection($Career)]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CareerRequest $request)
    {
        $validated = $request->validated();

        $Career = Career::create($validated);

        return response()->json([
            'data' => new CareerResource($Career),
            'message' => trans('crud.created'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Career = Career::findOrFail($id);

        return response()->json(['data' => new CareerResource($Career)], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CareerRequest $request, string $id)
    {
        $Career = Career::findOrFail($id);

        $Career->update($request->validated());

        return response()->json([
            'data' => new CareerResource($Career),
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
