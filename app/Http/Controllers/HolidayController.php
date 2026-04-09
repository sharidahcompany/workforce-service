<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Requests\HolidayRequest;
use App\Http\Resources\HolidayResource;
use App\Models\Tenant\Holiday;
use App\Services\QueryBuilderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function __construct(private QueryBuilderService $queryBuilder) {}

    public function index(Request $request): JsonResponse
    {
        $holidays = Holiday::query();

        $result = $this->queryBuilder->applyQuery($request, $holidays);

        return response()->json([
            'data' => HolidayResource::collection($result),
        ]);
    }

    public function store(HolidayRequest $request): JsonResponse
    {
        $holiday = Holiday::create($request->validated());

        return response()->json([
            'message' => trans('crud.created'),
            'data' => new HolidayResource($holiday),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $holiday = Holiday::findOrFail($id);

        return response()->json([
            'data' => new HolidayResource($holiday),
        ]);
    }

    public function update(HolidayRequest $request, string $id): JsonResponse
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->update($request->validated());

        return response()->json([
            'message' => trans('crud.updated'),
            'data' => new HolidayResource($holiday),
        ]);
    }

    public function destroy(DeleteRequest $request): JsonResponse
    {
        Holiday::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => trans('crud.deleted'),
        ]);
    }
}
