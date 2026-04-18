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
    case UNPAID = 'unpaid';
    case PENDING_PAYMENT = 'pending_payment';
    case PAID = 'paid';
    case PUBLISHED = 'published';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => 'Pending Assignment',
            self::UNDER_REVIEW => 'Under Review',
            self::REVISION_REQUIRED => 'Revision Required',
            self::ACCEPTED => 'Accepted (Waiting for Payment)',
            self::REJECTED => 'Rejected',
            self::PAID => 'Accepted (Payment Received)',
            self::PENDING_PAYMENT => 'Pending Payment',
            self::UNPAID => 'Unpaid',
            self::PUBLISHED => 'Published',
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
            self::PAID => 'success',
            self::PUBLISHED => 'success',
        };
    }
}