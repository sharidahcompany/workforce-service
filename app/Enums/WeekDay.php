<?php

namespace App\Enums;

enum WeekDay: int
{
    case MONDAY = 1;
    case TUESDAY = 2;
    case WEDNESDAY = 3;
    case THURSDAY = 4;
    case FRIDAY = 5;
    case SATURDAY = 6;
    case SUNDAY = 7;

    public function label(): string
    {
        return match ($this) {
            self::MONDAY => trans('weekdays.monday'),
            self::TUESDAY => trans('weekdays.tuesday'),
            self::WEDNESDAY => trans('weekdays.wednesday'),
            self::THURSDAY => trans('weekdays.thursday'),
            self::FRIDAY => trans('weekdays.friday'),
            self::SATURDAY => trans('weekdays.saturday'),
            self::SUNDAY => trans('weekdays.sunday'),
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ], self::cases());
    }
}
