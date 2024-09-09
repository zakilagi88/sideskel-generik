<?php

namespace App\Livewire\Components\Admin;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use ShuvroRoy\FilamentSpatieLaravelBackup\Components\BackupDestinationListRecords;
use ShuvroRoy\FilamentSpatieLaravelBackup\Models\BackupDestination;
use Spatie\Backup\BackupDestination\Backup;
use Spatie\Backup\BackupDestination\BackupDestination as SpatieBackupDestination;


class BackupListRecords extends BackupDestinationListRecords
{

    public function table(Table $table): Table
    {
        /** @var \App\Models\User */
        $auth = Filament::auth()->user();


        return parent::table($table)
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label(__('filament-spatie-backup::backup.components.backup_destination_list.table.actions.download'))
                    ->button()
                    ->color('primary')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible($auth->can('download-backup'))
                    ->action(
                        function (BackupDestination $record) {
                            $storage = Storage::disk($record->disk);
                            return $storage->download($record->path);
                        }
                    ),
                Tables\Actions\Action::make('delete')
                    ->label(__('filament-spatie-backup::backup.components.backup_destination_list.table.actions.delete'))
                    ->button()
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->visible($auth->can('delete-backup'))
                    ->requiresConfirmation()
                    ->action(function (BackupDestination $record) {
                        SpatieBackupDestination::create($record->disk, config('backup.backup.name'))
                            ->backups()
                            ->first(function (Backup $backup) use ($record) {
                                return $backup->path() === $record->path;
                            })
                            ->delete();

                        Notification::make()
                            ->title(__('filament-spatie-backup::backup.pages.backups.messages.backup_delete_success'))
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                // ...
            ]);
    }

    protected function afterBooted(): void {}
}
