<?php

namespace App\Enums;

enum CareerStatus: string
{
    case INACTIVE = 'inactive';
    case ACTIVE = 'active';

    public function label(): string
    {
        return match ($this) {
            self::INACTIVE => trans('enum.inactive'),
            self::ACTIVE => trans('enum.active'),
        };
    }

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
