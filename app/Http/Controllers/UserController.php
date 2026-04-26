<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Tenant\User\User;
use App\Services\QueryBuilderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private QueryBuilderService $queryBuilder) {}


    public function index(Request $request)
    {
        $users = User::query()->with('branch','department','career','experiences');

        $result = $this->queryBuilder->applyQuery($request, $users);

        return UserResource::collection($result);
    }

    public function store(UserRequest $request)
    {
        $user = User::create($request->validated());
        if ($request->hasFile('avatar')) {
            $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }

        return response()->json([
            'data' => new UserResource($user),
            'message' => trans('crud.created'),
        ], 201);
    }

    public function show(string $id)
    {
        $user = User::with('branch','department','career','experiences')->find($id);

        return response()->json(['data' => new UserResource($user)], 200);
    }

    public function update(UserRequest $request, string $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->validated());

        if ($request->hasFile('avatar')) {
            $user->clearMediaCollection('avatar');
            $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }

        return response()->json([
            'data' => new UserResource($user),
            'message' => trans('crud.updated'),
        ]);
    }

    public function destroy(DeleteRequest $request): JsonResponse
    {
        $ids = $request->input('ids', []);

        if (collect($ids)->contains(fn($id) => (int) $id === 1)) {
            return response()->json([
                'message' => trans('crud.delete.owner.error'),
            ], 422);
        }

        try {
            User::whereIn('id', $ids)->delete();
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
