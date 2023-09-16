<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KartukeluargaResource\Pages;
use App\Filament\Resources\KartukeluargaResource\RelationManagers;
use App\Filament\Resources\KartukeluargaResource\RelationManagers\PenduduksRelationManager;
use App\Models\Kartukeluarga;
use App\Models\RT;
use App\Models\RW;
use App\Models\SLS;


use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Arr;

class KartukeluargaResource extends Resource
{
    protected static ?string $model = Kartukeluarga::class;

    protected static ?string $recordTitleAttribute = 'kepalaKeluarga.nama_lengkap';

    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund';

    protected static ?string $navigationLabel = 'Kartu Keluarga';

    protected static ?string $slug = 'kartukeluarga';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->heading('Kartu Keluarga')
                    ->description('Informasi Umum')
                    ->schema([
                        Group::make()
                            ->relationship('kepalaKeluarga')
                            ->label('Kepala Keluarga')
                            ->schema(
                                [
                                    TextInput::make('nama_lengkap')
                                        ->label('Kepala Keluarga')
                                        ->required(),
                                ]
                            ),

                        Textarea::make('kk_alamat')
                            ->label('Alamat')
                            ->required(),
                        TextInput::make('kk_id')
                            ->label('No KK')
                            ->default('KK-' . random_int(1000000000000000, 9999999999999999))
                            ->disabled()
                            ->dehydrated()
                            ->required(),

                    ])

                    ->columnSpan(['lg' => 1]),

                Section::make('Wilayah')
                    ->description('Informasi Wilayah')
                    ->schema([
                        Group::make()
                            ->label('kk')
                            ->schema([
                                TextInput::make(('Kelurahan')),
                                Select::make('sls_id')
                                    ->searchable()
                                    ->preload()
                                    ->label('SLS')
                                    ->options(
                                        SLS::pluck('sls_nama', 'sls_id')
                                    ),

                            ])->columns(2),


                    ])
                    ->columnSpan(['lg' => 1]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                // Split::make([
                //     Stack::make([
                //         TextColumn::make('kepalaKeluarga.nama_lengkap')
                //             ->label('Kepala Keluarga')
                //             ->searchable()
                //             ->weight('medium')
                //             ->sortable(),
                //         TextColumn::make('kk_alamat')
                //             ->label('Alamat KK')
                //             ->searchable()
                //             ->alignLeft()
                //             ->color('gray')
                //             ->sortable(),

                //     ]),

                //     Stack::make([
                //         TextColumn::make('sls.rw_groups.rw_nama')
                //             ->label('RW')
                //             ->searchable()
                //             ->alignLeft()
                //             ->color('gray')
                //             ->sortable(),
                //         TextColumn::make('sls.rt_groups.rt_nama')
                //             ->label('RT')
                //             ->searchable()
                //             ->alignLeft()
                //             ->color('gray')
                //             ->sortable(),
                //     ])
                // ])

                TextColumn::make('kk_id')
                    ->label('No KK')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kk_alamat')
                    ->label('Alamat KK')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sls.rw_groups.rw_nama')
                    ->label('RW')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sls.rt_groups.rt_nama')
                    ->label('RT')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kepalaKeluarga.nama_lengkap')
                    ->label('Kepala Keluarga')

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])

            ->headerActions([
                CreateAction::make()->label('Kartu Keluarga Baru'),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PenduduksRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKartukeluargas::route('/'),
            'create' => Pages\CreateKartukeluarga::route('/create'),
            'edit' => Pages\EditKartukeluarga::route('/{record}/edit'),
        ];
    }



    public static function getRecordTitle(?Model $record): Htmlable | string
    {
        $kk_id = Arr::get(request()->route()->parameters, 'record');

        $kepalaKeluarga = Kartukeluarga::with('kepalaKeluarga')->find($kk_id);

        if (!$kepalaKeluarga) {
            return '';
        }

        return $kepalaKeluarga->kepalaKeluarga->nama_lengkap . ' - ' . $kepalaKeluarga->kk_id;
        // return parent::getRecordTitle($record);
    }
    // {
    //     // ambil kk_id untuk mengambil data kepalaKeluarga
    //     $kk_id = Arr::get(request()->route()->parameters, 'record');

    //     // ambil data kepalaKeluarga
    //     $kepalaKeluarga = Kartukeluarga::with('kepalaKeluarga')->get()->pluck('kepalaKeluarga.nama_lengkap', 'kk_id')->get($kk_id) ?? null;
    //     // jika data kepalaKeluarga tidak ditemukan, maka kembalikan null
    //     if (!$kepalaKeluarga) {
    //         return null;
    //     }

    //     return $kepalaKeluarga;
    // }
}
