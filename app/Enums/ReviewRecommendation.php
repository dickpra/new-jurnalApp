<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ReviewRecommendation: string implements HasLabel, HasColor
{
    case ACCEPT = 'accept';
    case MINOR_REVISION = 'minor_revision';
    case MAJOR_REVISION = 'major_revision';
    case REJECT = 'reject';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ACCEPT => 'Accept Submission',
            self::MINOR_REVISION => 'Minor Revision Required',
            self::MAJOR_REVISION => 'Major Revision Required',
            self::REJECT => 'Decline Submission',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::ACCEPT => 'success',
            self::MINOR_REVISION => 'info',
            self::MAJOR_REVISION => 'warning',
            self::REJECT => 'danger',
        };
    }
}