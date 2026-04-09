<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ExperienceRequest;
use App\Http\Resources\ExperienceResource;
use App\Models\Tenant\Experience;
use App\Services\QueryBuilderService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExperienceController extends Controller
{
    public function __construct(private QueryBuilderService $queryBuilder) {}

    public function index(Request $request): JsonResponse
    {
        $experiences = Experience::query()->with('jobApplication');

        $result = $this->queryBuilder->applyQuery($request, $experiences);

        return response()->json([
            'data' => ExperienceResource::collection($result),
        ]);
    }

    public function store(ExperienceRequest $request): JsonResponse
    {
        $experience = Experience::create($request->validated());
        $experience->load('jobApplication');

        return response()->json([
            'message' => trans('crud.created'),
            'data' => new ExperienceResource($experience),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $experience = Experience::with('jobApplication')->findOrFail($id);

        return response()->json([
            'data' => new ExperienceResource($experience),
        ]);
    }

    public function update(ExperienceRequest $request, string $id): JsonResponse
    {
        $experience = Experience::findOrFail($id);
        $experience->update($request->validated());
        $experience->load('jobApplication');

        return response()->json([
            'message' => trans('crud.updated'),
            'data' => new ExperienceResource($experience),
        ]);
    }

    public function destroy(DeleteRequest $request): JsonResponse
    {
        Experience::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => trans('crud.deleted'),
        ]);
    }
}
