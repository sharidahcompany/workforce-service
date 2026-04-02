<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuffetRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Resources\BuffetResource;
use App\Models\Tenant\Buffet;
use Illuminate\Http\Request;

class BuffetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buffets = Buffet::with('branch')->get();

        return response()->json(['data' => BuffetResource::collection($buffets)], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(BuffetRequest $request)
    {
        $buffet = Buffet::create($request->validated());

        return response()->json([
            'data' => new BuffetResource($buffet),
            'message' => trans('crud.created'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $buffet = Buffet::with('branch', 'buffetItems', 'buffetItems.media')->findOrFail($id);

        return response()->json(['data' => new BuffetResource($buffet)], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(BuffetRequest $request, string $id)
    {
        $buffet = Buffet::findOrFail($id);

        $buffet->update($request->validated());

        return response()->json([
            'data' => new BuffetResource($buffet),
            'message' => trans('crud.updated'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteRequest $request)
    {
        $ids = $request->input('ids');

        try {
            Buffet::whereIn('id', $ids)->delete();
        } catch (\Throwable $exception) {
            return response()->json([
                'message' => trans('crud.delete.error'),
            ], 400);
        }

        return response()->json([
            'message' => trans('crud.deleted'),
        ]);
    }
}
