<?php

namespace App\Livewire\Pages\Profil;

use App\Filament\Clusters\HalamanDesa\Resources\DeskelProfileResource;
use App\Infolists\Components\TableListEntry;
use App\Livewire\Templates\SimplePage;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;
use Filament\Infolists\Components\TextEntry;

class ProfilDisplay extends SimplePage
{
    protected static string $resource = DeskelProfileResource::class;

    protected static string $heading = 'Profil Desa Kelurahan';

    protected static bool $isCluster = true;

    protected static string $parameter = 'deskel_id';

    public function infolist(Infolist $infolist): Infolist
    {
        if (!$this->record) {
            return $infolist;
        }

        $extraStyle = fn (?string $state) => is_null($state)
            ? [] : ['class' => 'border-solid border-gray-400 pb-1 dark:border-gray-600 border-b hover:bg-gray-100'];
        return static::$resource::infolist($infolist)->record($this->record);
    }

    // protected function mutateRecord($record)
    // {
    //     $mutated = collect($record['data']);
    //     $record['data'] = $mutated->map(function ($item) {
    //         $entitas = array_map(function ($row) {
    //             ksort($row);
    //             return array_map('strval', $row);
    //         }, $item['entitas']);

    //         $header = array_unique(array_merge(array_keys($entitas[0] ?? []), ...array_map(function ($entita) {
    //             return array_keys($entita);
    //         }, $entitas)));

    //         $header = array_map(function ($item) {
    //             return preg_replace('/^\d+\s/', '', ucwords(str_replace('_', ' ', $item)));
    //         }, $header);

    //         ksort($header);

    //         return [
    //             'extra' => $item['extra'] ?? [],
    //             'label' => $item['label'],
    //             'entitas' => array_merge([$header], $entitas),
    //         ];
    //     })->toArray();

    //     return $record;
    // }

    protected function getPageSlug(): string
    {
        return $this->record->deskel_id ?? '';
    }

    protected function getPageHeading(): string
    {
        return $this->record->nama ?? static::$heading;
    }

    public function render()
    {
        return view('livewire.templates.infolist-page');
    }
}