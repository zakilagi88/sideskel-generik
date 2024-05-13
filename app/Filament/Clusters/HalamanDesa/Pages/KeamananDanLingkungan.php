<?php

namespace App\Filament\Clusters\HalamanDesa\Pages;

use App\Facades\Deskel;
use App\Filament\Clusters\HalamanDesa;
use App\Models\DesaKelurahanProfile;
use App\Models\KeamananDanLingkungan as ModelsKeamananDanLingkungan;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Support\Facades\FilamentView;
use Throwable;

use function Filament\Support\is_app_url;

class KeamananDanLingkungan extends Page implements HasForms, HasActions
{

    use InteractsWithForms, InteractsWithActions;
    use CanUseDatabaseTransactions;
    use InteractsWithFormActions;
    use HasUnsavedDataChangesAlert;


    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.clusters.halaman-desa.pages.keamanan-dan-lingkungan';

    protected static ?string $cluster = HalamanDesa::class;

    protected static ?string $slug = 'keamanan-dan-lingkungan';

    protected static ?string $title = 'Keamanan dan Lingkungan';

    protected ?string $heading = 'Keamanan dan Lingkungan';

    public ModelsKeamananDanLingkungan $records;

    public ?array $data = [];


    public function mount(ModelsKeamananDanLingkungan $records): void
    {
        $this->records = $records->all() ? $records : new ModelsKeamananDanLingkungan();
        $this->extraForm->fill($this->records->toArray());
    }


    protected function getForms(): array
    {
        return ['extraForm'];
    }

    public function extraForm(Form $form): Form
    {
        $deskel = Deskel::getFacadeRoot();
        return $form
            ->model($this->records)
            ->statePath('data')
            ->schema([
                Hidden::make('deskel_profil_id')
                    ->formatStateUsing(fn () => $deskel->id)
                    ->default($deskel->id),
                Section::make('Data Keamanan dan Lingkungan')
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
                        TableRepeater::make('jumlah_kejadiam_kriminalitas')
                            ->inlineLabel()
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

                Section::make('Data Lingkungan Hidup')
                    ->schema([
                        TextInput::make('wabah_menular')
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
                            ->label('Jumlah Pos Kesehatan')
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('Masukkan jumlah lokasi pencemaran tanah')
                            ->inlineLabel()
                            ->suffix('Lokasi'),
                        TextInput::make('Jumlah Pos Hutan Lindung')
                            ->label('Jumlah Pos Kesehatan')
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('Masukkan jumlah pos hutan lindung')
                            ->inlineLabel()
                            ->suffix('Lokasi'),
                    ]),

            ]);
    }

    /**
     * @return array<Action | ActionGroup>
     */
    public function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
        ];
    }

    public function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label(__('Simpan'))
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    public function getSubmitFormAction(): Action
    {
        return $this->getSaveFormAction();
    }

    public function save(): void
    {
        try {
            $this->beginDatabaseTransaction();

            $data = $this->extraForm->getState();
            dd($data);

            $datalama = DesaKelurahanProfile::find($this->records->id);



            if ($datalama) {
                $datalama->update($data);

                $this->commitDatabaseTransaction();

                Notification::make()
                    ->title('Data ' . $this->records->sebutan . $this->records->records_nama . ' berhasil diperbarui')
                    ->body('Silahkan cek kembali data yang telah diperbarui.')
                    ->success()
                    ->seconds(5)
                    ->persistent()
                    ->send();

                $this->redirect(route('filament.admin.pages.records-profile'));
            } else {
                DesaKelurahanProfile::create($data);

                $this->commitDatabaseTransaction();


                Notification::make()
                    ->title('Data ' . $this->records->sebutan . $this->records->records_nama . ' berhasil ditambahkan')
                    ->body('Silahkan cek kembali data yang telah ditambahkan.')
                    ->success()
                    ->seconds(5)
                    ->persistent()
                    ->send();

                $this->redirect(route('filament.admin.pages.records-profile'));
            }
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            throw $exception;
        }
        $this->rememberData();

        if ($redirectUrl = $this->getRedirectUrl()) {
            $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
        }
    }

    public function getRedirectUrl(): ?string
    {
        return null;
    }
}
