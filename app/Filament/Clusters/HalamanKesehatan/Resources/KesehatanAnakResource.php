<?php

namespace App\Filament\Clusters\HalamanKesehatan\Resources;

use App\Filament\Clusters\HalamanKesehatan\Resources\KesehatanAnakResource\Pages;
use App\Filament\Clusters\HalamanKesehatan;
use App\Models\KesehatanAnak;
use App\Services\GenerateStatusAnak;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\{Select, TextInput};
use Filament\Forms\{Form, Get, Set};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class KesehatanAnakResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = KesehatanAnak::class;

    protected static ?string $navigationIcon = 'fas-baby';

    protected static ?string $slug = 'anak';

    protected static ?string $cluster = HalamanKesehatan::class;

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
                                    ->relationship('anak', 'nama_lengkap')
                                    ->live(onBlur: true)
                                    ->label('Nama Anak')
                                    ->afterStateUpdated(function (Select $component, Set $set) {
                                        $id = $component->getState();
                                        $relasi = $component->getRelationship()->getRelated()->where('nik', $id)->first();
                                        $set('tanggal_lahir', $relasi->tanggal_lahir);
                                        $set('umur', $relasi->umur);
                                        $set('jenis_kelamin', $relasi->jenis_kelamin->value);
                                        $set('nama_ibu', $relasi->nama_ibu);
                                        // $component->getContainer()->getComponent('anak')->getChildComponentContainer()
                                        //     ->fill([
                                        //         'tanggal_lahir' => $relasi->tanggal_lahir,
                                        //         'jenis_kelamin' => $relasi->jenis_kelamin,
                                        //         'nama_ibu' => $relasi->nama_ibu,
                                        //     ]);
                                    }),
                                TextInput::make('tanggal_lahir')
                                    ->readOnly()
                                    ->formatStateUsing(
                                        function (Model $record) {
                                            $umur = Carbon::parse($record->anak->tanggal_lahir)->diffInMonths(now());
                                            return round($umur, 0);
                                        }

                                    )
                                    ->suffix('Bulan')
                                    ->placeholder('Umur dalam Bulan')
                                    ->maxLength(16),
                                Forms\Components\TextInput::make('jenis_kelamin')
                                    ->hiddenOn('create')
                                    ->formatStateUsing(
                                        function (TextInput $component) {
                                            if (!$component->getRecord()) {
                                                return null;
                                            }
                                            $jk = $component->getRecord()->anak->jenis_kelamin->value;
                                            return $jk;
                                        }
                                    )
                                    ->placeholder('Jenis Kelamin')
                                    ->maxLength(16),
                                Forms\Components\TextInput::make('nama_ibu')
                                    ->placeholder('Nama Ibu')
                                    ->maxLength(16),
                                Forms\Components\TextInput::make('berat_badan')
                                    ->placeholder('Berat Badan')
                                    ->live(onBlur: true)
                                    ->suffix('Gram')
                                    ->numeric()
                                    ->afterStateUpdated(
                                        function (Get $get, Set $set) {
                                            if ($get('berat_badan') == null) {
                                                return;
                                            }
                                            $indeksBbu = GenerateStatusAnak::getBbUIndeks(
                                                (int) $get('berat_badan'),
                                                (int) $get('umur'),
                                                $get('jenis_kelamin')
                                            );

                                            $set('z_score_bbu', $indeksBbu);
                                            $set('kategori_bbu', GenerateStatusAnak::getStatusBbU($indeksBbu));
                                        }
                                    )
                                    ->required(),
                                Forms\Components\TextInput::make('tinggi_badan')
                                    ->placeholder('Tinggi Badan')
                                    ->live(onBlur: true)
                                    ->suffix('Cm')
                                    ->required()
                                    ->afterStateUpdated(
                                        function (Get $get, Set $set) {
                                            if ($get('tinggi_badan') == null) {
                                                return;
                                            }
                                            $indeksTbu = GenerateStatusAnak::getTbUIndeks(
                                                (int) $get('tinggi_badan'),
                                                (int) $get('umur'),
                                                $get('jenis_kelamin')
                                            );

                                            $set('z_score_tbu', $indeksTbu);
                                            $set('kategori_tbu', GenerateStatusAnak::getStatusTbU($indeksTbu));
                                        }
                                    )
                                    ->numeric(),
                                Forms\Components\TextInput::make('imt')
                                    ->label('Indeks Massa Tubuh')
                                    ->placeholder('Indeks Massa Tubuh')
                                    ->live(onBlur: true)
                                    ->readOnly()
                                    ->afterStateUpdated(
                                        function (Get $get, Set $set) {
                                            if ($get('umur') == null) {
                                                return;
                                            }
                                            $indeksImtu = GenerateStatusAnak::getImtUIndeks(
                                                (int) $get('imt'),
                                                (int) $get('umur'),
                                                $get('jenis_kelamin')
                                            );

                                            $set('z_score_imtu', $indeksImtu);
                                            $set('kategori_imtu', GenerateStatusAnak::getStatusImtU($indeksImtu));
                                        }
                                    )
                                    ->formatStateUsing(
                                        function (Get $get) {
                                            if ($get('berat_badan') == null || $get('tinggi_badan') == null) {
                                                return null;
                                            }
                                            $imt = GenerateStatusAnak::getImt(
                                                (int) $get('berat_badan'),
                                                (int) $get('tinggi_badan'),
                                                (int) $get('umur')
                                            );

                                            return $imt;
                                        }
                                    )
                                    ->numeric(),

                                Forms\Components\Hidden::make('z_score_tbu')
                                    ->formatStateUsing(
                                        function (Get $get) {
                                            if ($get('tinggi_badan') == null) {
                                                return null;
                                            }
                                            return GenerateStatusAnak::getTbUIndeks(
                                                (int) $get('tinggi_badan'),
                                                (int) $get('umur'),
                                                $get('jenis_kelamin')
                                            );
                                        }
                                    ),
                                Forms\Components\Hidden::make('kategori_tbu')
                                    ->live()
                                    ->formatStateUsing(
                                        function (Get $get) {
                                            if ($get('z_score_tbu') == null) {
                                                return null;
                                            }
                                            return GenerateStatusAnak::getStatusTbU(
                                                (int) $get('z_score_tbu'),
                                            );
                                        }
                                    ),

                                Forms\Components\Hidden::make('z_score_bbu')
                                    ->live()
                                    ->formatStateUsing(
                                        function (Get $get) {
                                            if ($get('berat_badan') == null) {
                                                return null;
                                            }
                                            return GenerateStatusAnak::getBbUIndeks(
                                                (int) $get('berat_badan'),
                                                (int) $get('umur'),
                                                $get('jenis_kelamin')
                                            );
                                        }
                                    ),
                                Forms\Components\Hidden::make('kategori_bbu')
                                    ->live()
                                    ->formatStateUsing(
                                        function (Get $get) {
                                            if ($get('z_score_bbu') == null) {
                                                return null;
                                            }
                                            return GenerateStatusAnak::getStatusBbU(
                                                (int) $get('z_score_bbu'),
                                            );
                                        }
                                    ),
                                Forms\Components\Hidden::make('z_score_imtu')
                                    ->live()
                                    ->formatStateUsing(
                                        function (Get $get) {
                                            if ($get('imt') == null) {
                                                return null;
                                            }
                                            return GenerateStatusAnak::getImtUIndeks(
                                                (int) $get('imt'),
                                                (int) $get('umur'),
                                                $get('jenis_kelamin')
                                            );
                                        }
                                    ),
                                Forms\Components\Hidden::make('kategori_imtu')
                                    ->live()
                                    ->formatStateUsing(
                                        function (Get $get) {
                                            if ($get('z_score_imtu') == null) {
                                                return null;
                                            }
                                            return GenerateStatusAnak::getStatusImtU(
                                                (int) $get('z_score_imtu'),
                                            );
                                        }
                                    ),
                                Forms\Components\Hidden::make('kategori_tb_bb')
                                    ->formatStateUsing(
                                        function (Get $get) {
                                            if ($get('z_score_tb_bb') == null) {
                                                return null;
                                            }
                                            return GenerateStatusAnak::getStatusTbBb(
                                                (int) $get('z_score_tb_bb'),
                                            );
                                        }
                                    ),
                                Forms\Components\Hidden::make('z_score_tb_bb')
                                    ->formatStateUsing(
                                        function (Get $get) {
                                            if ($get('tinggi_badan') == null || $get('berat_badan') == null) {
                                                return null;
                                            }
                                            return GenerateStatusAnak::getTbBbIndeks(
                                                $get('tinggi_badan'),
                                                (int) $get('berat_badan'),
                                                $get('jenis_kelamin')
                                            );
                                        }
                                    )

                            ]),
                    ]),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('anak.nama_lengkap')
                    ->placeholder('Nama Anak')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->placeholder('Nama Ibu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('berat_badan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tinggi_badan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('imt')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kategori_tbu')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('z_score_tbu')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kategori_bbu')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('z_score_bbu')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kategori_imtu')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('z_score_imtu')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kategori_tb_bb')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                //
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