<?php

namespace App\Filament\Clusters\HalamanDesa\Resources\DeskelProfileResource\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\DeskelProfileResource;
use App\Filament\Pages\Dashboard;
use App\Settings\GeneralSettings;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\App;

class EditDeskelProfile extends EditRecord
{
    protected static string $resource = DeskelProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Kembali')
                ->url($this->previousUrl ?? $this->getResource()::getUrl('index')),
        ];
    }

    protected function getRedirectUrl(): string
    {
        $settings = app(GeneralSettings::class)->toArray();
        return $settings['site_active'] ? $this->getResource()::getUrl('index') : Dashboard::getUrl();
    }
}
