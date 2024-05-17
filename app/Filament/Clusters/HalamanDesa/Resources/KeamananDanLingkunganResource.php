<?php

namespace App\Filament\Clusters\HalamanDesa\Resources;

use App\Facades\Deskel;
use App\Filament\Clusters\HalamanDesa;
use App\Filament\Clusters\HalamanDesa\Resources\KeamananDanLingkunganResource\Pages;
use App\Filament\Clusters\HalamanDesa\Resources\KeamananDanLingkunganResource\RelationManagers;
use App\Models\KeamananDanLingkungan;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KeamananDanLingkunganResource extends Resource
{
    protected static ?string $model = KeamananDanLingkungan::class;

    protected static ?string $navigationIcon = 'fas-shield-halved';

    protected static ?string $cluster = HalamanDesa::class;

    protected static ?string $slug = 'keamanan-dan-lingkungan';

    protected static ?int $navigationSort = 5;


    public static function form(Form $form): Form
    {
        $deskelProfile = Deskel::getFacadeRoot();

        return $form
            ->schema([
                Hidden::make('deskel_profil_id')
                    ->default(
                        fn () => $deskelProfile->id ?? null
                    ),
                TextInput::make('jenis')
                    ->inlineLabel()
                    ->disabledOn('edit')
                    ->required(),
                static::getKeamananForms()->hidden(fn (Get $get) => $get('jenis') !== 'keamanan-dan-ketertiban'),
                static::getLingkunganForms()->hidden(fn (Get $get) => $get('jenis') !== 'lingkungan-hidup'),
            ]);
    }

    public static function getFormFieldKeys(): array
    {
        return [
            'keamanan-dan-ketertiban' => ['jumlah_anggota_linmas', 'jumlah_pos_kamling', 'jumlah_operasi_penertiban', 'jumlah_kejadian_kriminalitas'],
            'lingkungan-hidup' => ['wabah_penyakit_menular', 'jumlah_pos_bencana_alam', 'tim_tanggap_dan_siaga_Bencana', 'jumlah_kejadian_bencana', 'jumlah_lokasi_pencemaran_tanah', 'jumlah_pos_hutan_lindung'],
        ];
    }

    public static function getKeamananForms(): Section
    {
        return
            Section::make('Data Keamanan dan Lingkungan')
            ->schema([
                Repeater::make('data')
                    ->hiddenLabel()
                    ->minItems(1)
                    ->extraAttributes([
                        'class' => 'fi-repeater-no-container',
                    ])
                    ->addable(fn (Model $record) => empty($record->data) ? true : false)
                    ->deletable(false)
                    ->reorderable(false)
                    ->schema([
                        TextInput::make('jumlah_anggota_linmas')
                            ->inlineLabel()
                            ->label('Jumlah Anggota Linmas')
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('Masukkan jumlah anggota Linmas')
                            ->suffix('orang'),
                        TextInput::make('jumlah_pos_kamling')
                            ->inlineLabel()
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('Masukkan jumlah pos kamling')
                            ->label('Jumlah Pos Kamling')
                            ->suffix('Buah'),
                        TextInput::make('jumlah_operasi_penertiban')
                            ->inlineLabel()
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('Masukkan jumlah operasi penertiban')
                            ->label('Jumlah Operasi Penertiban')
                            ->suffix('Kali'),
                        TableRepeater::make('jumlah_kejadian_kriminalitas')
                            ->key('kriminalitas')
                            ->headers([
                                Header::make('jenis_kejadian_kriminalitas_h')
                                    ->label('Jenis Kejadian'),
                                Header::make('jumlah_kejadian_kriminalitas_h')
                                    ->label('Jumlah Kejadian'),
                            ])
                            ->label('Jumlah Kejadian Kriminalitas')
                            ->schema([
                                TextInput::make('jenis_kejadian_kriminalitas')
                                    ->label('Jenis Kejadian'),
                                TextInput::make('jumlah_kejadian_kriminalitas')
                                    ->label('Jumlah Pos Bencana Alam')
                                    ->suffix('Kasus'),
                            ]),
                    ]),

            ]);
    }

    public static function getLingkunganForms(): Section
    {
        return Section::make('Data Lingkungan Hidup')
            ->schema([
                Repeater::make('data')
                    ->hiddenLabel()
                    ->minItems(1)
                    ->extraAttributes([
                        'class' => 'fi-repeater-no-container',
                    ])
                    ->addable(fn (Model $record) => empty($record->data) ? true : false)
                    ->deletable(false)
                    ->reorderable(false)
                    ->schema([
                        TextInput::make('wabah_penyakit_menular')
                            ->inlineLabel()
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('Masukkan jumlah wabah menular')
                            ->label('Wabah Menular')
                            ->suffix('Kasus'),
                        TextInput::make('jumlah_pos_bencana_alam')
                            ->inlineLabel()
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('Masukkan jumlah pos bencana alam')
                            ->label('Jumlah Pos Bencana Alam')
                            ->suffix('Buah'),
                        TextInput::make('tim_tanggap_dan_Siaga_Bencana')
                            ->inlineLabel()
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('Masukkan jumlah tim tanggap dan siaga bencana')
                            ->label('Tim Tanggap dan Siaga Bencana')
                            ->suffix('Kali'),
                        TableRepeater::make('jumlah_kejadian_bencana')
                            ->label('Jumlah Kejadian Bencana')
                            ->inlineLabel()
                            ->key('bencana')
                            ->headers([
                                Header::make('jenis_kejadian_bencana_h')
                                    ->label('Jenis Kejadian'),
                                Header::make('jumlah_kejadian_bencana_h')
                                    ->label('Jumlah Kejadian'),
                            ])
                            ->schema([
                                TextInput::make('jenis_kejadian_bencana')
                                    ->label('Jenis Kejadian'),
                                TextInput::make('jumlah_kejadian_bencana')
                                    ->label('Jumlah Pos Bencana Alam')
                                    ->suffix('Kali'),
                            ]),
                        TextInput::make('jumlah_lokasi_pencemaran_tanah')
                            ->label('Jumlah Lokasi Pencemaran Tanah')
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('Masukkan jumlah lokasi pencemaran tanah')
                            ->inlineLabel()
                            ->suffix('Lokasi'),
                        TextInput::make('jumlah_pos_hutan_lindung')
                            ->label('Jumlah Pos Hutan Lindung')
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('Masukkan jumlah pos hutan lindung')
                            ->inlineLabel()
                            ->suffix('Lokasi'),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jenis')
                    ->formatStateUsing(fn ($state) => ucwords(str_replace('_', ' ', $state)))
                    ->weight(FontWeight::SemiBold)
                    ->searchable()
                    ->sortable(),
                Panel::make([
                    Split::make([
                        TextColumn::make('data')
                            ->getStateUsing(function ($record) {
                                $labelKeys = static::getFormFieldKeys()[$record->jenis];
                                $formattedLabelKeys = array_map(fn ($label) => static::formattedLabel($label), $labelKeys);
                                return $formattedLabelKeys;
                            })
                            ->listWithLineBreaks()
                            ->bulleted()
                    ])->from('md'),
                ])->collapsed(false)
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->using(
                        function (Model $record, array $data) {
                            $formFieldKeys = static::getFormFieldKeys();
                            $data['data'] = array_map(function ($item) use ($formFieldKeys, $record) {
                                $item = array_filter($item, function ($value, $key) use ($formFieldKeys, $record) {
                                    return in_array($key, $formFieldKeys[$record->jenis]);
                                }, ARRAY_FILTER_USE_BOTH);
                                return $item;
                            }, $data['data']);

                            return $record->update($data);
                        }
                    ),


                //
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function formattedLabel(string $label): string
    {
        return ucwords(str_replace('_', ' ', $label));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageKeamananDanLingkungans::route('/'),
        ];
    }
}
