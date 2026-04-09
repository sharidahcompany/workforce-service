<?php

namespace App\Enums;

enum InterviewStatus: string
{
    case SCHEDULED = 'scheduled';
    case CANCELED = 'canceled';
    case RESCHEDULED = 'rescheduled';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::SCHEDULED => trans('enum.scheduled'),
            self::CANCELED => trans('enum.canceled'),
            self::RESCHEDULED => trans('enum.rescheduled'),
            self::ACCEPTED => trans('enum.accepted'),
            self::REJECTED => trans('enum.rejected'),
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return array_map(fn($case) => [
            'label' => $case->label(),
            'value' => $case->value,
        ], self::cases());
    }
}
