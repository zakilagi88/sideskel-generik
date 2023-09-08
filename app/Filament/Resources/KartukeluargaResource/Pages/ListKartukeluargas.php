<?php

namespace App\Filament\Resources\KartukeluargaResource\Pages;

use App\Filament\Resources\KartukeluargaResource;
use App\Models\KartuKeluarga;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

class ListKartukeluargas extends ListRecords
{
    protected static string $resource = KartukeluargaResource::class;

    public $file = '';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getHeader(): ?View
    {
        $data = Actions\CreateAction::make();
        return view('filament.custom.upload-file', compact('data'));
    }

    public function save()
    {
        KartuKeluarga::create(
            [
                'kk_no' => 329123903,
                'kk_alamat' => 'Jalan Indonesia',
                'rt_id' => 1,
                'rw_id' => 2
            ]
        )
            ->save();
    }
}
