<?php

namespace App\Filament\Clusters\HalamanDesa\Resources\AparaturResource\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\AparaturResource;
use App\Models\Penduduk;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAparatur extends CreateRecord
{
    protected static string $resource = AparaturResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $isPenduduk = $data['is_penduduk'] == 'terdata';

        if ($isPenduduk) {
            $data = $this->getPendudukData($data);
        }

        return $data;
    }

    protected function getPendudukData(array $data): array
    {
        $penduduk = Penduduk::where('nik', $data['nik'])->first();

        $data['nama'] = $penduduk->nama_lengkap;
        $data['tempat_lahir'] = $penduduk->tempat_lahir;
        $data['tanggal_lahir'] = $penduduk->tanggal_lahir;
        $data['jenis_kelamin'] = $penduduk->jenis_kelamin;
        $data['agama'] = $penduduk->agama;
        $data['pendidikan'] = $penduduk->pendidikan;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
