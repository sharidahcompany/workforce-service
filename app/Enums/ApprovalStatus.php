<?php

namespace App\Enums;

enum ApprovalStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => trans('approval_status.pending'),
            self::APPROVED => trans('approval_status.approved'),
            self::REJECTED => trans('approval_status.rejected'),
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
