<?php

namespace App\Filament\Clusters\HalamanDesa\Resources\DeskelProfileResource\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\DeskelProfileResource;
use App\Filament\Pages\Dashboard;
use App\Settings\GeneralSettings;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class ListDeskelProfiles extends ListRecords
{
    protected static string $resource = DeskelProfileResource::class;

    protected array $settings;

    public function mount(): void
    {
        $this->settings = app(GeneralSettings::class)->toArray();
    }

    public function getBreadcrumb(): ?string
    {
        return 'Identitas ' . $this->settings['sebutan_deskel'];
    }


    public function getTitle(): string | Htmlable
    {
        return 'Identitas ' . $this->settings['sebutan_deskel'];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('beranda')
                ->label('Beranda')
                ->icon('fas-home')
                ->url(Dashboard::getUrl())
        ];
    }
}
