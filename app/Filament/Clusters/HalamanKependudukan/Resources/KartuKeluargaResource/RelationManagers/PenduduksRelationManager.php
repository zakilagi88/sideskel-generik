<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\KartuKeluargaResource\RelationManagers;

use App\Filament\Clusters\HalamanKependudukan\Resources\PendudukResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class PenduduksRelationManager extends RelationManager
{
    protected static string $relationship = 'penduduks';

    protected static ?string $title = 'Anggota Keluarga';

    protected static ?string $recordTitleAttribute = 'nik';

    public function form(Form $form): Form
    {
        return PendudukResource::form($form);
    }


    public function table(Table $table): Table
    {
        return PendudukResource::table($table)
            ->heading('Anggota Keluarga')
            ->paginated(false);
    }
}
