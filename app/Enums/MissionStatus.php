<?php

namespace App\Enums;

enum MissionStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => trans('mission_status.pending'),
            self::IN_PROGRESS => trans('mission_status.in_progress'),
            self::COMPLETED => trans('mission_status.completed'),
            self::CANCELLED => trans('mission_status.cancelled'),
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
