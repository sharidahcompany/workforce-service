<?php

namespace App\Http\Controllers;

use App\Http\Requests\BenefitRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Resources\BenefitResource;
use App\Models\Tenant\Benefit;
use Illuminate\Http\Request;

class BenefitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $benefits = Benefit::latest()->get();
        return response()->json(['data' => BenefitResource::collection($benefits)]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BenefitRequest $request)
    {
        $validated = $request->validated();
        $benefit = Benefit::create($validated);
        return response()->json([
            'data' => new BenefitResource($benefit),
            'message' => trans('crud.created'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $benefit = Benefit::findOrFail($id);
        return response()->json(['data' => new BenefitResource($benefit)], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BenefitRequest $request, string $id)
    {
        $benefit = Benefit::findOrFail($id);
        $benefit->update($request->validated());
        return response()->json([
            'data' => new BenefitResource($benefit),
            'message' => trans('crud.updated'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteRequest $request)
    {
        $ids = $request->input('ids', []);

        try {
            Benefit::whereIn('id', $ids)->delete();
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
