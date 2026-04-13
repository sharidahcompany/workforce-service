<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ExperienceRequest;
use App\Http\Resources\ExperienceResource;
use App\Models\Tenant\Experience;
use App\Services\QueryBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExperienceController extends Controller
{
    public function __construct(private QueryBuilderService $queryBuilder) {}

    public function index(Request $request): JsonResponse
    {
        $experiences = Experience::query()->with('user');

        $result = $this->queryBuilder->applyQuery($request, $experiences);

        return response()->json([
            'data' => ExperienceResource::collection($result),
        ]);
    }

    public function store(ExperienceRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $userId = $validated['user_id'];
        $experiences = $validated['experiences'];

        $incomingIds = collect($experiences)->pluck('id')->filter()->toArray();
        $types = collect($experiences)->pluck('type')->unique()->toArray();

        return DB::transaction(function () use ($userId, $experiences, $incomingIds, $types) {

            Experience::where('user_id', $userId)
                ->whereIn('type', $types)
                ->whereNotIn('id', $incomingIds)
                ->delete();

            $processedExperiences = collect();

            foreach ($experiences as $data) {
                $experience = Experience::updateOrCreate(
                    [
                        'id' => $data['id'] ?? null,
                        'user_id' => $userId
                    ],
                    $data
                );

                $processedExperiences->push($experience);
            }

            return response()->json([
                'message' => trans('crud.updated'),
                'data' => ExperienceResource::collection($processedExperiences),
            ], 200);
        });
    }

    public function show(string $id): JsonResponse
    {
        $experience = Experience::with('user')->findOrFail($id);

        return response()->json([
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
