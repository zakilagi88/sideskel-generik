<?php

namespace App\Filament\Clusters\HalamanKesehatan\Resources;

use App\Filament\Clusters\HalamanKesehatan\Resources\KesehatanAnakResource\Pages;
use App\Filament\Clusters\HalamanKesehatan;
use App\Filament\Exports\KesehatanAnakExporter;
use App\Models\KesehatanAnak;
use App\Services\GenerateStatusAnak;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Carbon\Carbon;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms;
use Filament\Forms\Components\{Group, Select, TextInput};
use Filament\Forms\{Form, Get, Set};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class KesehatanAnakResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = KesehatanAnak::class;

    protected static ?string $navigationIcon = 'fas-baby';

    protected static ?string $slug = 'anak';

    protected static ?string $cluster = HalamanKesehatan::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make([
                    'default' => 1,
                    'sm' => 2,
                    'md' => 3,
                    'lg' => 4,
                    'xl' => 6,
                    '2xl' => 8,
                ])
                    ->schema([
                        Forms\Components\Group::make()
                            ->columnSpanFull()
                            ->columns(2)
                            ->live()
                            ->schema([
                                Forms\Components\Placeholder::make('view_bbu')
                                    ->hiddenLabel()
                                    ->columns(1)
                                    ->content(
                                        function (Get $get) {
                                            $bgColor = 'primary';
                                            $indeks = "BB/U";
                                            $status = $get('kategori_bbu');
                                            $nilai = $get('z_score_bbu');
                                            return view('filament.pages.components.info-kesehatan-anak', compact('indeks', 'status', 'nilai', 'bgColor'));
                                        }
                                    ),
                                Forms\Components\Placeholder::make('view_tbu')
                                    ->hiddenLabel()
                                    ->columns(1)
                                    ->content(
                                        function (Get $get) {
                                            $bgColor = 'secondary';
                                            $indeks = "TB/U";
                                            $status = $get('kategori_tbu');
                                            $nilai = $get('z_score_tbu');

                                            return view('filament.pages.components.info-kesehatan-anak', compact('indeks', 'status', 'nilai', 'bgColor'));
                                        }
                                    ),
                                Forms\Components\Placeholder::make('view_imtu')
                                    ->hiddenLabel()
                                    ->columns(1)
                                    ->content(
                                        function (Get $get) {
                                            $bgColor = 'warning';
                                            $indeks = "IMT/U";
                                            $status = $get('kategori_imtu');
                                            $nilai = $get('z_score_imtu');
                                            return view('filament.pages.components.info-kesehatan-anak', compact('indeks', 'status', 'nilai', 'bgColor'));
                                        }
                                    ),
                                Forms\Components\Placeholder::make('view_tbbb')
                                    ->hiddenLabel()
                                    ->columns(1)
                                    ->content(
                                        function (Get $get) {
                                            $bgColor = 'info';
                                            $indeks = "TB/BB";
                                            $status = $get('kategori_tb_bb');
                                            $nilai = $get('z_score_tb_bb');
                                            return view('filament.pages.components.info-kesehatan-anak', compact('indeks', 'status', 'nilai', 'bgColor'));
                                        }
                                    ),


                            ]),
                        Forms\Components\Group::make()
                            ->columnSpanFull()
                            ->columns(2)
                            ->schema([
                                Forms\Components\Select::make('nama_lengkap')
                                    ->relationship(name: 'anak', modifyQueryUsing: fn ($query) => $query->with('wilayah')->whereDoesntHave('kesehatanAnak'))
                                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->nama_lengkap} {$record->wilayah?->wilayah_nama}")
                                    ->live(onBlur: true)
                                    ->label('Nama Anak')
                                    ->hiddenOn('edit')
                                    ->afterStateUpdated(function (Select $component, Set $set) {
                                        $id = $component->getState();
                                        $relasi = $component->getRelationship()->getRelated()->where('nik', $id)->first();

                                        if ($relasi == null) {
                                            $set('umur', null);
                                            $set('jenis_kelamin', null);
                                            $set('nama_ibu', null);

                                            return;
                                        }

                                        $set('umur', round(Carbon::parse($relasi->tanggal_lahir)->diffInMonths(now())), 0);
                                        $set('jenis_kelamin', $relasi->jenis_kelamin->value);
                                        $set('nama_ibu', $relasi->nama_ibu);
                                    }),
                                Forms\Components\Select::make('nama_lengkap')
                                    ->disabled()
                                    ->relationship(name: 'anak', modifyQueryUsing: fn ($query) => $query->with('wilayah'))
                                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->nama_lengkap} {$record->wilayah?->wilayah_nama}")
                                    ->live(onBlur: true)
                                    ->label('Nama Anak')
                                    ->hiddenOn('create')

                                    ->afterStateUpdated(function (Select $component, Set $set) {
                                        $id = $component->getState();
                                        $relasi = $component->getRelationship()->getRelated()->where('nik', $id)->first();

                                        if ($relasi == null) {
                                            $set('umur', null);
                                            $set('jenis_kelamin', null);
                                            $set('nama_ibu', null);

                                            return;
                                        }

                                        $set('umur', round(Carbon::parse($relasi->tanggal_lahir)->diffInMonths(now())), 0);
                                        $set('jenis_kelamin', $relasi->jenis_kelamin->value);
                                        $set('nama_ibu', $relasi->nama_ibu);
                                    }),

                                TextInput::make('umur')
                                    ->readOnly()
                                    ->live()
                                    ->formatStateUsing(fn ($record) => (is_null($record)) ? null :  round(Carbon::parse($record->anak?->tanggal_lahir)->diffInMonths(now()), 0))
                                    ->suffix('Bulan')
                                    ->placeholder('Umur dalam Bulan')
                                    ->maxLength(16),
                                Forms\Components\TextInput::make('jenis_kelamin')
                                    ->readOnly()
                                    ->live()
                                    ->formatStateUsing(fn ($record) => (is_null($record)) ? null : $record->anak?->jenis_kelamin->value)
                                    ->placeholder('Jenis Kelamin')
                                    ->maxLength(16),
                                Forms\Components\TextInput::make('nama_ibu')
                                    ->placeholder('Nama Ibu')
                                    ->readOnly()
                                    ->formatStateUsing(fn ($record) => (is_null($record)) ? null : $record->anak?->nama_ibu)
                                    ->maxLength(16),
                                Forms\Components\TextInput::make('berat_badan')
                                    ->placeholder('Berat Badan')
                                    ->live(onBlur: true)
                                    ->suffix('Gram')
                                    ->numeric()
                                    ->afterStateUpdated(
                                        function (Get $get, Set $set) {
                                            $tinggiBadan = $get('tinggi_badan');
                                            $umur = (int) $get('umur');
                                            $jenisKelamin = $get('jenis_kelamin');
                                            $beratBadan = $get('berat_badan');

                                            if ($get('berat_badan') == null) {
                                                return;
                                            }


                                            $indeksBbu = GenerateStatusAnak::getBbUIndeks((int) $get('berat_badan'), $umur, $jenisKelamin);
                                            $set('z_score_bbu', $indeksBbu);
                                            $set('kategori_bbu', GenerateStatusAnak::getStatusBbU($indeksBbu));

                                            if ($beratBadan === null || $tinggiBadan === null) {
                                                return;
                                            }

                                            $imt = GenerateStatusAnak::getImt((int) $beratBadan, (int) $tinggiBadan, $umur);
                                            $set('imt', $imt);

                                            $z_score_imtu = GenerateStatusAnak::getImtUIndeks($imt, $umur, $jenisKelamin);
                                            $set('z_score_imtu', $z_score_imtu);
                                            $set('kategori_imtu', GenerateStatusAnak::getStatusImtU($z_score_imtu));

                                            $z_score_tb_bb = GenerateStatusAnak::getTbBbIndeks((int) $tinggiBadan, (int) $beratBadan, $jenisKelamin);
                                            $set('z_score_tb_bb', $z_score_tb_bb);
                                            $set('kategori_tb_bb', GenerateStatusAnak::getStatusTbBb($z_score_tb_bb));
                                        }
                                    )
                                    ->required(),
                                Forms\Components\TextInput::make('tinggi_badan')
                                    ->placeholder('Tinggi Badan')
                                    ->live(onBlur: true)
                                    ->suffix('Cm')
                                    ->required()
                                    ->numeric()
                                    ->afterStateUpdated(
                                        function (Get $get, Set $set) {
                                            $tinggiBadan = $get('tinggi_badan');
                                            $umur = (int) $get('umur');
                                            $jenisKelamin = $get('jenis_kelamin');
                                            $beratBadan = $get('berat_badan');

                                            if ($tinggiBadan === null) {
                                                return;
                                            }

                                            $indeksTbu = GenerateStatusAnak::getTbUIndeks((int) $tinggiBadan, $umur, $jenisKelamin);
                                            $set('z_score_tbu', $indeksTbu);
                                            $set('kategori_tbu', GenerateStatusAnak::getStatusTbU($indeksTbu));

                                            if ($beratBadan === null || $tinggiBadan === null) {
                                                return;
                                            }

                                            $imt = GenerateStatusAnak::getImt((int) $beratBadan, (int) $tinggiBadan, $umur);
                                            $set('imt', $imt);

                                            $z_score_imtu = GenerateStatusAnak::getImtUIndeks($imt, $umur, $jenisKelamin);
                                            $set('z_score_imtu', $z_score_imtu);
                                            $set('kategori_imtu', GenerateStatusAnak::getStatusImtU($z_score_imtu));

                                            $z_score_tb_bb = GenerateStatusAnak::getTbBbIndeks((int) $tinggiBadan, (int) $beratBadan, $jenisKelamin);
                                            $set('z_score_tb_bb', $z_score_tb_bb);
                                            $set('kategori_tb_bb', GenerateStatusAnak::getStatusTbBb($z_score_tb_bb));
                                        }

                                    ),
                                Forms\Components\Hidden::make('imt')->reactive(),
                                Forms\Components\Hidden::make('z_score_tbu')->reactive(),
                                Forms\Components\Hidden::make('kategori_tbu')->reactive(),
                                Forms\Components\Hidden::make('z_score_bbu')->reactive(),
                                Forms\Components\Hidden::make('kategori_bbu')->reactive(),
                                Forms\Components\Hidden::make('z_score_imtu')->reactive(),
                                Forms\Components\Hidden::make('kategori_imtu')->reactive(),
                                Forms\Components\Hidden::make('kategori_tb_bb')->reactive(),
                                Forms\Components\Hidden::make('z_score_tb_bb')->reactive(),

                            ]),
                    ]),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('anak.nama_lengkap')
                    ->label('Nama Anak')
                    ->description(fn ($record) => $record->anak?->wilayah?->wilayah_nama ?? 'Wilayah Tidak Diketahui')
                    ->placeholder('Nama Anak')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_ibu')
                    ->label('Ibu')
                    ->placeholder(fn ($record) => $record->anak?->nama_ibu ?? 'Ibu Tidak Diketahui')
                    ->searchable(),
                Tables\Columns\TextColumn::make('berat_badan')
                    ->numeric()
                    ->suffix(' gram')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tinggi_badan')
                    ->numeric()
                    ->suffix(' cm')
                    ->sortable(),
                Tables\Columns\TextColumn::make('imt')
                    ->numeric()
                    ->label('Indeks Massa Tubuh')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kategori_tbu')
                    ->label('Kategori TB/U')
                    ->sortable(),
                Tables\Columns\TextColumn::make('z_score_tbu')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kategori_bbu')
                    ->label('Kategori BB/U')
                    ->sortable(),
                Tables\Columns\TextColumn::make('z_score_bbu')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kategori_imtu')
                    ->label('Kategori IMT/U')
                    ->sortable(),
                Tables\Columns\TextColumn::make('z_score_imtu')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kategori_tb_bb')
                    ->label('Kategori TB/BB')
                    ->sortable(),
                Tables\Columns\TextColumn::make('z_score_tb_bb')
                    ->numeric()
                    ->sortable()
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
                SelectFilter::make('kategori_tbu')
                    ->label('Kategori TB/U')
                    ->options([
                        'Sangat pendek (severely stunted)' => 'Sangat pendek (severely stunted)',
                        'Pendek (stunted)' => 'Pendek (stunted)',
                        'Normal' => 'Normal',
                        'Tinggi' => 'Tinggi',
                    ]),
                SelectFilter::make('kategori_bbu')
                    ->label('Kategori BB/U')
                    ->options([
                        'Berat Badan sangat kurang (severely underweight)' => 'Berat Badan sangat kurang (severely underweight)',
                        'Berat Badan kurang (underweight)' => 'Berat Badan kurang (underweight)',
                        'Berat Badan normal (normal)' => 'Berat Badan normal (normal)',
                        'Risiko Berat Badan lebih (overweight)' => 'Risiko Berat Badan lebih (overweight)',
                    ]),
                SelectFilter::make('kategori_imtu')
                    ->label('Kategori IMT/U')
                    ->options([
                        'Gizi buruk (severely wasted)' => 'Gizi Buruk (severely wasted)',
                        'Gizi kurang (wasted)' => 'Gizi Kurang (wasted)',
                        'Gizi baik (normal)' => 'Gizi Baik (normal)',
                        'Berisiko gizi lebih (possible risk of overweight)' => 'Berisiko gizi lebih (possible risk of overweight)',
                        'Gizi lebih (overweight)' => 'Gizi lebih (overweight)',
                        'Obesitas (obese)' => 'Obesitas (obese)',
                    ]),
                SelectFilter::make('kategori_tb_bb')
                    ->label('Kategori TB/BB')
                    ->options([
                        'Gizi buruk (severely wasted)' => 'Gizi Buruk (severely wasted)',
                        'Gizi kurang (wasted)' => 'Gizi Kurang (wasted)',
                        'Gizi baik (normal)' => 'Gizi Baik (normal)',
                        'Berisiko gizi lebih (possible risk of overweight)' => 'Berisiko gizi lebih (possible risk of overweight)',
                        'Gizi lebih (overweight)' => 'Gizi lebih (overweight)',
                        'Obesitas (obese)' => 'Obesitas (obese)',
                    ]),
            ], FiltersLayout::AboveContent)
            ->filtersFormColumns(2)
            ->filtersFormSchema(fn (array $filters): array => [
                Group::make()
                    ->extraAttributes(['class' => 'mb-4'])
                    ->schema([
                        $filters['kategori_tbu'],
                        $filters['kategori_bbu'],
                        $filters['kategori_imtu'],
                        $filters['kategori_tb_bb'],
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(KesehatanAnakExporter::class)
                    ->color('primary')
                    ->label('Ekspor Data')
                    ->formats([
                        ExportFormat::Xlsx,
                        ExportFormat::Csv,
                    ])
                    ->columnMapping(),
            ])
            ->actions([

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKesehatanAnaks::route('/'),
            'create' => Pages\CreateKesehatanAnak::route('/create'),
            'edit' => Pages\EditKesehatanAnak::route('/{record}/edit'),
        ];
    }
}
