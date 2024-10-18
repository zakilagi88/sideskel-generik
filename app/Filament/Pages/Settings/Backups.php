<?php

namespace App\Filament\Pages\Settings;

use App\Filament\Pages\Dashboard;
use App\Jobs\CreateBackupJob;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Support\Htmlable;
use ShuvroRoy\FilamentSpatieLaravelBackup\Enums\Option;
use ShuvroRoy\FilamentSpatieLaravelBackup\Pages\Backups as BaseBackups;

class Backups extends BaseBackups
{
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

    protected static string $view = 'filament.pages.backups';


    protected function getHeaderActions(): array
    {
        return [
            Action::make('beranda')
                ->label('Beranda')
                ->icon('fas-home')
                ->url(Dashboard::getUrl()),
        ];
    }


    public function getHeading(): string | Htmlable
    {
        return 'Backup Data Aplikasi';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Core';
    }

    public function create(string $option = ''): void
    {
        /** @var FilamentSpatieLaravelBackupPlugin $plugin */
        $plugin = filament()->getPlugin('filament-spatie-backup');

        CreateBackupJob::dispatch(Option::from($option), $plugin->getTimeout())
            ->onQueue($plugin->getQueue())
            ->afterResponse();

        $this->dispatch('close-modal', id: 'backup-option');

        Notification::make()
            ->title(__('filament-spatie-backup::backup.pages.backups.messages.backup_success'))
            ->success()
            ->send();
    }
}
