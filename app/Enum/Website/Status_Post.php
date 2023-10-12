<?php

namespace App\Enum\Website;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;


enum Status_Post: string implements HasLabel, HasColor, HasIcon
{
    case draft = 'draft';
    case publish = 'publish';
    case pending = 'pending';
    case private = 'private';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::draft => 'draft',
            self::publish => 'publish',
            self::pending => 'pending',
            self::private => 'private',
        };
    }

    public function getColor(): string | null | array
    {
        return match ($this) {
            self::draft => 'primary',
            self::publish => 'success',
            self::pending => 'info',
            self::private => 'danger',
        };
    }

    // public function getIcon(): string | null
    // {
    //     return match ($this) {
    //         self::draft => 'heroicon-o-document-duplicate',
    //         self::publish => 'heroicon-o-document',
    //         self::pending => 'heroicon-o-clock',
    //         self::private => 'heroicon-o-lock-closed',
    //     };
    // }

    // using fontawesome
    public function getIcon(): string | null
    {
        return match ($this) {
            self::draft => 'fas-file-alt',
            self::publish => 'fas-file-signature',
            self::pending => 'fas-clock',
            self::private => 'fas-lock',
        };
    }
}
