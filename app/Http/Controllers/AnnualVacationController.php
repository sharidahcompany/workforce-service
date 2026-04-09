<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnnualVacationRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Resources\AnnualVacationResource;
use App\Models\Tenant\AnnualVacation;
use App\Services\QueryBuilderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnnualVacationController extends Controller
{
    public function __construct(private QueryBuilderService $queryBuilder) {}

    public function index(Request $request): JsonResponse
    {
        $annualVacations = AnnualVacation::query()->with('user');

        $result = $this->queryBuilder->applyQuery($request, $annualVacations);

        return response()->json([
            'data' => AnnualVacationResource::collection($result),
        ]);
    }

    public function store(AnnualVacationRequest $request): JsonResponse
    {
        $annualVacation = AnnualVacation::create($request->validated());
        $annualVacation->load('user');

        return response()->json([
            'message' => trans('crud.created'),
            'data' => new AnnualVacationResource($annualVacation),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $annualVacation = AnnualVacation::with('user')->findOrFail($id);

        return response()->json([
            'data' => new AnnualVacationResource($annualVacation),
        ]);
    }

    public function update(AnnualVacationRequest $request, string $id): JsonResponse
    {
        $annualVacation = AnnualVacation::findOrFail($id);
        $annualVacation->update($request->validated());
        $annualVacation->load('user');

        return response()->json([
            'message' => trans('crud.updated'),
            'data' => new AnnualVacationResource($annualVacation),
        ]);
    }

    public function destroy(DeleteRequest $request): JsonResponse
    {
        AnnualVacation::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => trans('crud.deleted'),
        ]);
    }
}
