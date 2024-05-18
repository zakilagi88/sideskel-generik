<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\PendudukResource\Pages;

use App\Filament\Clusters\HalamanKependudukan\Resources\PendudukResource;
use Filament\Actions;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Pages\EditRecord;

class EditPenduduk extends EditRecord
{
    protected static string $resource = PendudukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function beforeFill(): void
    {
        // Runs before the form fields are populated from the database.

        // if ($this->record->audits()->latest()->first() == null) {
        //     $this->record->fill($this->record->toArray());
        // } else
        //     $this->record->fill($this->record->audits()->latest()->first()->old_values);    
    }

    protected function afterFill(): void
    {
        // jika data audit terakhir tidak ada maka isi form dengan data dari database

    }

    protected function beforeValidate(): void
    {
        // Runs before the form fields are validated when the form is saved.
    }

    protected function afterValidate(): void
    {
        // Runs after the form fields are validated when the form is saved.
    }

    protected function beforeSave(): void
    {
        // Runs before the form fields are saved to the database.

    }

    protected function afterSave(): void
    {
        // Runs after the form fields are saved to the database.
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        //cek apakah data telah huruf besar semua jika belum maka ubah ke huruf besar semua
        if (ctype_upper($data['nama_lengkap']) && ctype_upper($data['alamat_sekarang']) && ctype_upper($data['tempat_lahir'])) {
            return $data;
        } else {
            $data['nama_lengkap'] = strtoupper($data['nama_lengkap']);
            $data['alamat_sekarang'] = strtoupper($data['alamat_sekarang']);
            $data['tempat_lahir'] = strtoupper($data['tempat_lahir']);
            return $data;
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($data['status_pengajuan'] == 'DALAM PROSES') {
            if ($this->record->audits()->latest()->first() == null) {
                return $data;
            } else {
                foreach ($this->record->audits()->latest()->first()->old_values as $key => $value) {
                    if (array_key_exists($key, $data)) {
                        $data[$key] = $value;
                    }
                }
                return $data;
            }
        } else {
            return $data;
        }
    }

    protected $listeners = [
        'auditRestored',
        'updateAuditsRelationManager',
    ];

    // public function auditRestored()
    // {
    //     // your code
    // }

    public function updated()
    {
        //jika data kk_id berubah maka tidak dispatch
        if ($this->record->kk_id == $this->record->getOriginal('kk_id')) {
            $this->dispatch('updateAuditsRelationManager');
        }
    }
}
