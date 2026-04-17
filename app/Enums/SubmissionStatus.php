<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SubmissionStatus: string implements HasLabel, HasColor
{
    case PENDING = 'pending';
    case UNDER_REVIEW = 'under_review';
    case REVISION_REQUIRED = 'revision_required';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => 'Pending Assignment',
            self::UNDER_REVIEW => 'Under Review',
            self::REVISION_REQUIRED => 'Revision Required',
            self::ACCEPTED => 'Accepted (Waiting for Payment)',
            self::REJECTED => 'Rejected',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::PENDING => 'gray',
            self::UNDER_REVIEW => 'info',
            self::REVISION_REQUIRED => 'warning',
            self::ACCEPTED => 'success',
            self::REJECTED => 'danger',
        };
    }
}