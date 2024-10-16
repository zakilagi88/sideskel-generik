<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\PendudukResource\Pages;

use App\Enums\Kependudukan\StatusPengajuanType;
use App\Filament\Clusters\HalamanKependudukan\Resources\PendudukResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Pages\EditRecord;

class EditPenduduk extends EditRecord
{
    protected static string $resource = PendudukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Kembali')
                ->url(route(static::$resource::getRouteBaseName() . '.index'))
                ->button(),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function beforeFill(): void {}

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
        /** @var \App\Models\User */
        $authUser = Filament::auth()->user();
        if ($authUser->hasRole('Operator Wilayah') && $data['status_pengajuan'] == 'TINJAU ULANG') {
            $data['status_pengajuan'] = StatusPengajuanType::BELUM_DIVERIFIKASI->value;
        } elseif ($authUser->hasRole('Admin') && $data['status_pengajuan'] == 'BELUM DIVERIFIKASI') {
            $data['status_pengajuan'] = StatusPengajuanType::DIVERIFIKASI->value;
        }
        //cek apakah data telah huruf besar semua jika belum maka ubah ke huruf besar semua
        if (ctype_upper($data['nama_lengkap']) && ctype_upper($data['alamat_sekarang']) && ctype_upper($data['tempat_lahir']) && ctype_upper($data['alamat_sebelumnya'])) {
            return $data;
        } else {
            $data['nama_lengkap'] = strtoupper($data['nama_lengkap']);
            $data['alamat_sekarang'] = strtoupper($data['alamat_sekarang']);
            $data['alamat_sebelumnya'] = strtoupper($data['alamat_sebelumnya']);
            $data['tempat_lahir'] = strtoupper($data['tempat_lahir']);
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
