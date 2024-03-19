<?php

namespace App\Enums\Desa;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;


enum StatusBeritaType: string implements HasLabel, HasColor, HasIcon
{
    case DRAFT = 'DRAFT';
    case PUBLISH = 'PUBLISH';
    case PENDING = 'PENDING';
    case PRIVATE = 'PRIVATE';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DRAFT => 'DRAFT',
            self::PUBLISH => 'PUBLISH',
            self::PENDING => 'PENDING',
            self::PRIVATE => 'PRIVATE',
        };
    }
    public function getColor(): string | null | array
    {
        return match ($this) {
            self::DRAFT => 'primary',
            self::PUBLISH => 'success',
            self::PENDING => 'info',
            self::PRIVATE => 'danger',
        };
    }

    public function getIcon(): string | null
    {
        return match ($this) {
            self::DRAFT => 'fas-file-alt',
            self::PUBLISH => 'fas-file-signature',
            self::PENDING => 'fas-clock',
            self::PRIVATE => 'fas-lock',
        };
    }
}
