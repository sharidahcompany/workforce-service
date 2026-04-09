<?php

namespace App\Http\Controllers;

use App\Enums\AttendanceStatus;
use App\Http\Requests\AttendanceRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Resources\AttendanceResource;
use App\Models\Tenant\Attendance;
use App\Services\QueryBuilderService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AttendanceController extends Controller
{
    public function __construct(private QueryBuilderService $queryBuilder) {}

    public function index(Request $request): JsonResponse
    {
        $attendances = Attendance::query()->with([
            'user',
            'shift',
        ]);

        $result = $this->queryBuilder->applyQuery($request, $attendances);

        return response()->json([
            'data' => AttendanceResource::collection($result),
        ]);
    }

    public function store(AttendanceRequest $request): JsonResponse
    {
        $attendance = Attendance::create($request->validated());
        $attendance->load([
            'user',
            'shift',
        ]);

        return response()->json([
            'message' => trans('crud.created'),
            'data' => new AttendanceResource($attendance),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $attendance = Attendance::with([
            'user',
            'shift',
        ])->findOrFail($id);

        return response()->json([
            'data' => new AttendanceResource($attendance),
        ]);
    }

    public function update(AttendanceRequest $request, string $id): JsonResponse
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->update($request->validated());
        $attendance->load([
            'user',
            'shift',
        ]);

        return response()->json([
            'message' => trans('crud.updated'),
            'data' => new AttendanceResource($attendance),
        ]);
    }

    public function destroy(DeleteRequest $request): JsonResponse
    {
        Attendance::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => trans('crud.deleted'),
        ]);
    }

    public function checkIn(string $id): JsonResponse
    {
        $attendance = Attendance::with('shift')->findOrFail($id);

        if ($attendance->status === AttendanceStatus::ABSENT) {
            return response()->json([
                'message' => trans('attendance.cannot_check_in_absent'),
            ], 422);
        }

        if ($attendance->check_in) {
            return response()->json([
                'message' => trans('attendance.already_checked_in'),
            ], 422);
        }

        $now = now();
        $graceMinutes = $attendance->shift?->grace_period ?? 0;
        $allowedCheckInTime = $attendance->shift_start_at->copy()->addMinutes($graceMinutes);

        $attendance->check_in = $now;

        if ($now->greaterThan($allowedCheckInTime)) {
            $attendance->status = AttendanceStatus::LATE;
            $attendance->late_minutes = $now->diffInMinutes($attendance->shift_start_at);
        } else {
            $attendance->status = AttendanceStatus::PRESENT;
            $attendance->late_minutes = 0;
        }

        $attendance->save();
        $attendance->load(['user', 'shift']);

        return response()->json([
            'message' => trans('attendance.checked_in_successfully'),
            'data' => new AttendanceResource($attendance),
        ]);
    }

    public function checkOut(string $id): JsonResponse
    {
        $attendance = Attendance::with('shift')->findOrFail($id);

        if (!$attendance->check_in) {
            return response()->json([
                'message' => trans('attendance.cannot_check_out_before_check_in'),
            ], 422);
        }

        if ($attendance->check_out) {
            return response()->json([
                'message' => trans('attendance.already_checked_out'),
            ], 422);
        }

        $now = now();

        $attendance->check_out = $now;

        if (($attendance->shift?->overtime_allowed ?? true) && $now->greaterThan($attendance->shift_end_at)) {
            $attendance->overtime_minutes = $now->diffInMinutes($attendance->shift_end_at);
        } else {
            $attendance->overtime_minutes = 0;
        }

        $attendance->requires_review = false;

        $attendance->save();
        $attendance->load(['user', 'shift']);

        return response()->json([
            'message' => trans('attendance.checked_out_successfully'),
            'data' => new AttendanceResource($attendance),
        ]);
    }
}
