<?php

namespace App\Enums;

enum MissionApprovalStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => trans('mission_approval_status.pending'),
            self::APPROVED => trans('mission_approval_status.approved'),
            self::REJECTED => trans('mission_approval_status.rejected'),
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
