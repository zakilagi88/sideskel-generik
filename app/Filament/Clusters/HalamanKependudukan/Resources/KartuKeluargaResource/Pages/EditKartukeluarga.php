<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\KartuKeluargaResource\Pages;

use App\Filament\Clusters\HalamanKependudukan\Resources\KartuKeluargaResource;
use App\Models\KartuKeluarga;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKartukeluarga extends EditRecord
{
    protected static string $resource = KartukeluargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // protected function mutateFormDataBeforeSave(array $data): array
    // {
    // jika kk id parentnya diubah, maka ubah juga kk id parent dari anak2nya yaitu anggota keluarga
    // if ($data['kk_id'] != $this->record->kk_id) {
    //     // buat kk id baru dengan data kk id lama
    //     $kkbaru = new KartuKeluarga(
    //         [
    //             'kk_no' => $this->record->kk_no,
    //             'kk_kepala' => null,
    //             'kk_alamat' => $this->record->kk_alamat,
    //             'sls_id' => $this->record->sls_id,
    //             'kel_id' => $this->record->kel_id,

    //         ]
    //     );
    //     $kkbaru->save();

    //     $anggotaKeluarga = \App\Models\AnggotaKeluarga::where('kk_id', $this->record->kk_id)->get();
    //     foreach ($anggotaKeluarga as $anggota) {
    //         $anggota->kk_id = null;
    //         $anggota->save();
    //     }
    // }

    // return $data;
    // }

    protected $listeners = [
        'auditRestored',
        'updateAuditsRelationManager',
    ];

    public function auditRestored()
    {
        // your code
    }

    public function updated()
    {
        $this->dispatch('updateAuditsRelationManager');
    }
}
