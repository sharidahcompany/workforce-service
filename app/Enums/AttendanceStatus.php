<?php

namespace App\Enums;

enum AttendanceStatus: string
{
    case PENDING = 'pending';
    case PRESENT = 'present';
    case LATE = 'late';
    case ABSENT = 'absent';
    case MISSED_CHECKOUT = 'missed_checkout';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => trans('enum.pending'),
            self::PRESENT => trans('enum.present'),
            self::LATE => trans('enum.late'),
            self::ABSENT => trans('enum.absent'),
        };
    }

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
