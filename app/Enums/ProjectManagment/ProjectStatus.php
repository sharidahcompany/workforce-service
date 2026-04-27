<?php

namespace App\Enums\ProjectManagment;

enum ProjectStatus: string
{
    case PENDING = 'pending';
    case ON_TRACK = 'on_track';
    case AT_RISK = 'at_risk';
    case BLOCKED = 'blocked';
    case ARCHIVED ='archived';

    case REJECTED = 'rejected';
    public function label(): string
    {
        return match ($this) {
            self::PENDING => trans('project_status.pending'),
            self::ON_TRACK => trans('project_status.on_track'),
            self::AT_RISK => trans('project_status.at_risk'),
            self::BLOCKED => trans('project_status.blocked'),
            self::ARCHIVED => trans('project_status.archived'),
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
