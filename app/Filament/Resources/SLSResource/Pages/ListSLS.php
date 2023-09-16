<?php

namespace App\Filament\Resources\SLSResource\Pages;

use App\Filament\Resources\SLSResource;
use App\Models\SLS;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListSLS extends ListRecords
{
    protected static string $resource = SLSResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $data = [];

        $sls_data = SLS::orderBy('rw_id')->orderBy('rt_id')->get();

        $current_rw_id = 0;

        foreach ($sls_data as $sls) {
            $rw_id = $sls->rw_id;

            if (!isset($data[$rw_id])) {
                $data[$rw_id] = Tab::make('RW ', $sls->rw_groups->rw_nama)
                    ->modifyQueryUsing(function (Builder $query) use ($rw_id) {
                        $query->where('rw_id', $rw_id);
                    })->label($sls->rw_groups->rw_nama);
            }
        }

        return
            [
                'all' => Tab::make('Semua', function (Builder $query) {
                    $query->where('sls_id', '!=', null);
                })->label('Semua'),
            ]
            + $data;
    }
}
