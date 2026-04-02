<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuffetItemRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Resources\BuffetItemResource;
use App\Models\Tenant\BuffetItem;

class BuffetItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $buffetItems = BuffetItem::with('media')->get();

        return response()->json(['data' => BuffetItemResource::collection($buffetItems)], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(BuffetItemRequest $request)
    {
        $buffetItem = BuffetItem::create($request->validated());

        if ($request->hasFile('file')) {
            $buffetItem->addMediaFromRequest('file')->toMediaCollection('buffet_items');
        }

        return response()->json([
            'data' => new BuffetItemResource($buffetItem),
            'message' => trans('crud.created'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $buffetItem = BuffetItem::with('media')->findOrFail($id);

        return response()->json(['data' => new BuffetItemResource($buffetItem)], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id, BuffetItemRequest $request)
    {
        $buffetItem = BuffetItem::findOrFail($id);

        $buffetItem->update($request->validated());

        if ($request->hasFile('file')) {
            $buffetItem->clearMediaCollection('buffet_items');
            $buffetItem->addMediaFromRequest('file')->toMediaCollection('buffet_items');
        }

        return response()->json([
            'data' => new BuffetItemResource($buffetItem),
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
            BuffetItem::whereIn('id', $ids)->delete();
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
