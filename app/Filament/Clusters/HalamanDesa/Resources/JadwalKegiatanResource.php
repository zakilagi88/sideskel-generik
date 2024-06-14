<?php

namespace App\Filament\Clusters\HalamanDesa\Resources;

use App\Filament\Clusters\HalamanDesa;
use App\Filament\Clusters\HalamanDesa\Resources\JadwalKegiatanResource\Pages;
use App\Filament\Clusters\HalamanDesa\Resources\JadwalKegiatanResource\RelationManagers;
use App\Models\Deskel\JadwalKegiatan;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JadwalKegiatanResource extends Resource
{
    protected static ?string $model = JadwalKegiatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // protected static ?string $cluster = HalamanDesa::class;

    protected static ?string $slug = 'jadwal-kegiatan';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('user_id')
                    ->default(auth()->id())
                    ->required(),
                Forms\Components\TextInput::make('nama_kegiatan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tempat_kegiatan')
                    ->required()
                    ->maxLength(255),
                DateTimePicker::make('tgl_mulai')
                    ->label('Tanggal Mulai')
                    ->native(false),
                DateTimePicker::make('tgl_selesai')
                    ->label('Tanggal Selesai')
                    ->native(false),
                Forms\Components\TextInput::make('keterangan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('status')
                    ->onIcon('fas-check')
                    ->onColor('success')
                    ->offIcon('fas-times')
                    ->offColor('danger')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_kegiatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tempat_kegiatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tgl_mulai')
                    ->date()
                    ->description(
                        fn (JadwalKegiatan $record) => 'Jam : ' . Carbon::parse($record->tgl_mulai)->format('H:i')
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('tgl_selesai')
                    ->date()
                    ->description(
                        fn (JadwalKegiatan $record) => 'Jam : ' . Carbon::parse($record->tgl_selesai)->format('H:i')
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make()->iconSize(IconSize::Small)->color('success')->modalWidth(MaxWidth::FiveExtraLarge),
                    Tables\Actions\EditAction::make()->iconSize(IconSize::Small)->color('primary'),
                    Tables\Actions\DeleteAction::make()->iconSize(IconSize::Small)->color('danger'),
                ])->icon("fas-gears")->iconPosition('after')->color('success')->button()->label('Aksi'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJadwalKegiatans::route('/'),
            'create' => Pages\CreateJadwalKegiatan::route('/create'),
            'edit' => Pages\EditJadwalKegiatan::route('/{record}/edit'),
        ];
    }
}
