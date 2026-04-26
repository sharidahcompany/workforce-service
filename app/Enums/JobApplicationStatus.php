<?php

namespace App\Enums;

enum JobApplicationStatus: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case ON_HOLD = 'on_hold';
    case WAITING = 'waiting';


    public function label(): string
    {
        return match ($this) {
            self::PENDING => trans('approval_status.pending'),
            self::ACCEPTED => trans('approval_status.accepted'),
            self::REJECTED => trans('approval_status.rejected'),
            self::ON_HOLD => trans('approval_status.on_hold'),    
            self::WAITING => trans('approval_status.waiting'),    
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
