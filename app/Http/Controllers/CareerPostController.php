<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Requests\CareerPostRequest;
use App\Http\Resources\CareerPostResource;
use App\Models\Tenant\CareerPost;
use App\Services\QueryBuilderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CareerPostController extends Controller
{
    public function __construct(private QueryBuilderService $queryBuilder) {}

    public function index(Request $request): JsonResponse
    {
        $careerPosts = CareerPost::query();

        $result = $this->queryBuilder->applyQuery($request, $careerPosts);

        return response()->json([
            'data' => CareerPostResource::collection($result),
        ]);
    }

    public function store(CareerPostRequest $request): JsonResponse
    {
        $careerPosts = CareerPost::create($request->validated());

        return response()->json([
            'message' => trans('crud.created'),
            'data' => new CareerPostResource($careerPosts),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $careerPosts = CareerPost::findOrFail($id);

        return response()->json([
            'data' => new CareerPostResource($careerPosts),
        ]);
    }

    public function update(CareerPostRequest $request, string $id): JsonResponse
    {
        $careerPosts = CareerPost::findOrFail($id);
        $careerPosts->update($request->validated());

        return response()->json([
            'message' => trans('crud.updated'),
            'data' => new CareerPostResource($careerPosts),
        ]);
    }

    public function destroy(DeleteRequest $request): JsonResponse
    {
        $ids = $request->input('ids', []);

        CareerPost::whereIn('id', $ids)->delete();

        return response()->json([
            'message' => trans('crud.deleted'),
        ]);
    }
}
