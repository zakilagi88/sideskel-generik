<?php

namespace App\Filament\Clusters\HalamanDesa\Resources;

use App\Filament\Clusters\HalamanDesa;
use App\Filament\Clusters\HalamanDesa\Resources\PeraturanResource\Pages;
use App\Models\Desa\Keputusan;
use App\Models\Desa\Peraturan;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Carbon\Carbon;
use Coolsam\FilamentFlatpickr\Forms\Components\Flatpickr;
use Filament\Forms;
use Filament\Forms\{Form, Get, Set};
use Filament\Infolists\Components\ViewEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class PeraturanResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Peraturan::class;

    protected static ?string $navigationIcon = 'fas-book';

    protected static ?string $navigationLabel = 'Buku Peraturan Desa';

    protected static ?string $slug = 'peraturan';

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
                Forms\Components\Grid::make([
                    'default' => 1,
                ])->schema([
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
                                ->directory('peraturan')
                                ->openable()
                                ->previewable()
                                ->downloadable()
                                ->getUploadedFileNameForStorageUsing(
                                    function (TemporaryUploadedFile $file, Get $get) {
                                        $prefix = str_replace(' ', '_', $get('../jenis'));
                                        $tentang = str_replace(' ', '_', 'Tentang_' . $get('../tentang'));
                                        $detail = str_replace(' ', '_', 'No_' . $get('../no_ditetapkan') . '_Tahun_' . Carbon::parse($get('../tgl_ditetapkan'))->format('Y'));
                                        $filetype = str_replace(' ', '_', $file->getClientOriginalExtension());
                                        return (string) $prefix . '_' . $detail . '_' . $tentang . '.' . $filetype;
                                    }
                                )
                                ->acceptedFileTypes(['application/pdf', 'application/msword']),

                        ]),
                    Forms\Components\Select::make('jenis')
                        ->label('Jenis Peraturan')
                        ->inlineLabel()
                        ->options([
                            'Peraturan Desa' => 'Peraturan Desa',
                            'Peraturan Bersama' => 'Peraturan Bersama',
                            'Peraturan Kepala Desa' => 'Peraturan Kepala Desa',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('tentang')
                        ->label('Tentang')
                        ->inlineLabel()

                        ->required()
                        ->maxLength(255)
                        ->columnStart(1),
                    Forms\Components\Textarea::make('uraian_singkat')
                        ->inlineLabel()
                        ->autosize()
                        ->label('Uraian Singkat'),
                    Forms\Components\TextInput::make('no_ditetapkan')
                        ->label('Nomor Ditetapkan Peraturan Desa/Kelurahan')
                        ->minValue(0)
                        ->inlineLabel()
                        ->numeric()
                        ->required(),
                    Flatpickr::make('tgl_ditetapkan')
                        ->label('Tanggal Ditetapkan Peraturan Desa/Kelurahan')
                        ->inlineLabel()
                        ->required(),
                    Flatpickr::make('tgl_kesepakatan')
                        ->inlineLabel()
                        ->label('Tanggal Kesepakatan Peraturan Desa/Kelurahan')
                        ->required(),
                    Forms\Components\TextInput::make('no_dilaporkan')
                        ->minValue(0)
                        ->inlineLabel()
                        ->label('Nomor Dilaporkan')
                        ->maxLength(255),
                    Flatpickr::make('tgl_dilaporkan')
                        ->inlineLabel()
                        ->label('Tanggal Dilaporkan'),
                    Forms\Components\TextInput::make('no_diundangkan_l')
                        ->label('Nomor Diundangkan di Lembaran Desa/Kelurahan')
                        ->minValue(0)
                        ->inlineLabel()
                        ->numeric()
                        ->required(),
                    Flatpickr::make('tgl_diundangkan_l')
                        ->inlineLabel()
                        ->label('Tanggal Diundangkan dalam Lembaran Desa/Kelurahan')
                        ->required(),
                    Forms\Components\TextInput::make('no_diundangkan_b')
                        ->minValue(0)
                        ->inlineLabel()
                        ->label('Nomor Diundangkan dalam Berita Desa/Kelurahan')
                        ->numeric()
                        ->required(),
                    Flatpickr::make('tgl_diundangkan_b')
                        ->inlineLabel()
                        ->label('Tanggal Diundangkan dalam Berita Desa/Kelurahan')
                        ->required(),
                    Forms\Components\Textarea::make('keterangan')
                        ->label('Keterangan')
                        ->inlineLabel()
                        ->autosize()
                        ->maxLength(255),

                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->rowIndex()
                    ->searchable(),
                Tables\Columns\TextColumn::make('dokumens.dok_nama')
                    ->label('Nama Dokumen')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis')
                    ->label('Jenis Peraturan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_ditetapkan')
                    ->getStateUsing(
                        function (Peraturan $record) {
                            return $record->no_ditetapkan . ' / ' . $record->tgl_ditetapkan->format('Y-m-d');
                        }
                    )
                    ->label('Nomor/Tanggal Ditetapkan')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tentang')
                    ->label('Tentang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('uraian_singkat')
                    ->label('Uraian Singkat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tgl_kesepakatan')
                    ->label('Tanggal Kesepakatan')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('no_dilaporkan')
                    ->getStateUsing(
                        function (Peraturan $record) {
                            return $record->no_dilaporkan . ' / ' . $record->tgl_dilaporkan;
                        }
                    )
                    ->label('Nomor/Tanggal Dilaporkan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('no_diundangkan_l')
                    ->getStateUsing(
                        function (Peraturan $record) {
                            return $record->no_diundangkan_l . ' / ' . $record->tgl_diundangkan_l;
                        }
                    )
                    ->label('Nomor/Tanggal Diundangkan di Lembaran Desa/Kelurahan')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('no_diundangkan_b')
                    ->getStateUsing(
                        function (Peraturan $record) {
                            return $record->no_diundangkan_b . ' / ' . $record->tgl_diundangkan_b;
                        }
                    )
                    ->label('Nomor/Tanggal Diundangkan di Berita Desa/Kelurahan')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListPeraturans::route('/'),
            'create' => Pages\CreatePeraturan::route('/create'),
            'edit' => Pages\EditPeraturan::route('/{record}/edit'),
        ];
    }
}
