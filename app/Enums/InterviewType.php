<?php

namespace App\Enums;

enum InterviewType: string
{
    case ON_SITE = 'on_site';
    case ONLINE = 'online';

    public function label(): string
    {
        return match ($this) {
            self::ON_SITE => trans('enum.ON_SITE'),
            self::ONLINE => trans('enum.ONLINE'),
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
