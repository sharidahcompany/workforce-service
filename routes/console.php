<?php

use App\Enums\AttendanceStatus;
use App\Models\Tenant\Attendance;
use App\Models\Tenant\Shift;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    $today = now()->startOfDay();
    $dayNumber = $today->isoWeekday();

    Shift::query()
        ->with('users')
        ->chunkById(100, function ($shifts) use ($today, $dayNumber) {
            foreach ($shifts as $shift) {
                $workDays = $shift->work_days ?? [];

                if (!in_array($dayNumber, $workDays, true)) {
                    continue;
                }

                $shiftStartAt = $today->copy()->setTimeFromTimeString($shift->start_time);
                $shiftEndAt = $today->copy()->setTimeFromTimeString($shift->end_time);

                if ($shiftEndAt->lessThanOrEqualTo($shiftStartAt)) {
                    $shiftEndAt->addDay();
                }

                foreach ($shift->users as $user) {
                    Attendance::query()->firstOrCreate(
                        [
                            'user_id' => $user->id,
                            'shift_id' => $shift->id,
                            'attendance_date' => $today->toDateString(),
                        ],
                        [
                            'shift_start_at' => $shiftStartAt,
                            'shift_end_at' => $shiftEndAt,
                            'status' => AttendanceStatus::PENDING->value,
                            'late_minutes' => 0,
                            'overtime_minutes' => 0,
                            'requires_review' => false,
                        ]
                    );
                }
            }
        });
})->everyFiveSeconds();

Schedule::call(function () {
    $currentTime = now();

    Attendance::query()
        ->with('shift')
        ->where('status', AttendanceStatus::PENDING->value)
        ->whereNull('check_in')
        ->where('requires_review', false)
        ->chunkById(200, function ($attendances) use ($currentTime) {
            foreach ($attendances as $attendance) {
                $graceMinutes = $attendance->shift?->grace_period ?? 0;
                $cutoff = $attendance->shift_start_at->copy()->addMinutes($graceMinutes);

                if ($currentTime->greaterThan($cutoff)) {
                    $attendance->update([
                        'status' => AttendanceStatus::ABSENT->value,
                    ]);
                }
            }
        });

    $reviewAfterHours = 24;

    Attendance::query()
        ->whereIn('status', [
            AttendanceStatus::PRESENT->value,
            AttendanceStatus::LATE->value,
        ])
        ->whereNotNull('check_in')
        ->whereNull('check_out')
        ->where('requires_review', false)
        ->chunkById(200, function ($attendances) use ($currentTime, $reviewAfterHours) {
            foreach ($attendances as $attendance) {
                $reviewAt = $attendance->shift_end_at->copy()->addHours($reviewAfterHours);

                if ($currentTime->greaterThan($reviewAt)) {
                    $attendance->update([
                        'requires_review' => true,
                    ]);
                }
            }
        });
})->everyFiveMinutes();
