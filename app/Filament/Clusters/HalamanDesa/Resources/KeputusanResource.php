<?php

namespace App\Filament\Clusters\HalamanDesa\Resources;

use App\Filament\Clusters\HalamanDesa;
use App\Filament\Clusters\HalamanDesa\Resources\KeputusanResource\Pages;
use App\Models\Desa\Keputusan;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Coolsam\FilamentFlatpickr\Forms\Components\Flatpickr;
use Filament\Forms;
use Filament\Forms\{Form, Get, Set};
use Filament\Infolists\Components\ViewEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class KeputusanResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Keputusan::class;

    protected static ?string $navigationIcon = 'fas-gavel';

    protected static ?string $navigationLabel = 'Buku Keputusan Kepala Desa';

    protected static ?string $slug = 'keputusan';

    protected static ?string $cluster = HalamanDesa::class;

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'restore',
            'restore_any',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('no')
                    ->label('Nomor Keputusan Kepala Desa')
                    ->numeric()
                    ->required()
                    ->maxLength(255),
                Flatpickr::make('tgl')
                    ->label('Tanggal Keputusan Kepala Desa')
                    ->required(),
                Forms\Components\TextInput::make('no_dilaporkan')
                    ->label('Nomor Dilaporkan')
                    ->maxLength(255),
                Flatpickr::make('tgl_dilaporkan')
                    ->label('Tanggal Dilaporkan'),
                Forms\Components\Textarea::make('tentang')
                    ->label('Tentang')
                    ->required()
                    ->autosize()
                    ->maxLength(255),
                Forms\Components\Textarea::make('uraian_singkat')
                    ->autosize()
                    ->label('Uraian Singkat'),
                Forms\Components\Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->autosize()
                    ->maxLength(255),
                Forms\Components\Fieldset::make()
                    ->relationship('dokumens')
                    ->schema([

                        Forms\Components\Hidden::make('dok_jenis')
                            ->required(
                                function (\Livewire\Component $livewire, Set $set) {
                                    $model = class_basename($livewire->getModel());
                                    return $set('dok_jenis', $model);
                                }
                            ),
                        Forms\Components\TextInput::make('dok_nama')
                            ->label('Nama Dokumen')
                            ->live()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('dok_path')
                            ->label('Dokumen')
                            ->live()
                            ->disk('public')
                            ->directory('keputusan')
                            ->openable()
                            ->previewable()
                            ->downloadable()
                            ->getUploadedFileNameForStorageUsing(
                                function (TemporaryUploadedFile $file, Get $get, Set $set) {
                                    if ($get('dok_nama') === null) {
                                        $filename = (string) str_replace(' ', '_', $file->getClientOriginalName());
                                    } else {
                                        $filename = (string) str_replace(' ', '_', $get('dok_nama')) . '.' . $file->getClientOriginalExtension();
                                    }

                                    return $set('dok_nama', $filename);
                                }
                            )
                            ->acceptedFileTypes(['application/pdf', 'application/msword']),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('No Urut')
                    ->alignCenter()
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('dokumens.dok_nama')
                    ->label('Nama Dokumen')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no')
                    ->label('Nomor/Tanggal Keputusan')
                    ->getStateUsing(
                        function (Keputusan $record) {
                            return $record->no . ' / ' . $record->tgl->format('Y-m-d');
                        }
                    )
                    ->searchable(),
                Tables\Columns\TextColumn::make('tentang')
                    ->label('Tentang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_dilaporkan')
                    ->getStateUsing(
                        function (Keputusan $record) {
                            return $record->no_dilaporkan . ' / ' . $record->tgl_dilaporkan;
                        }
                    )
                    ->label('Nomor/Tanggal Dilaporkan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('keterangan')
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
                Tables\Actions\Action::make('Preview File')
                    ->label('')
                    ->color('success')
                    ->icon('fas-eye')
                    ->button()
                    ->infolist([
                        ViewEntry::make('dokumens')
                            ->view('filament.pages.preview-file'),
                    ])
                    ->iconSize('md')
                    ->extraAttributes([
                        'class' => 'text-green-500 hover:text-green-700 mr-2',
                    ]),
                Tables\Actions\EditAction::make()->label('')->button(),
            ], ActionsPosition::BeforeColumns)
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
            'index' => Pages\ListKeputusans::route('/'),
            'create' => Pages\CreateKeputusan::route('/create'),
            'edit' => Pages\EditKeputusan::route('/{record}/edit'),
        ];
    }
}