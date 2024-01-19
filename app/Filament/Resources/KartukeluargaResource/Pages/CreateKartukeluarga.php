<?php

namespace App\Filament\Resources\KartukeluargaResource\Pages;

use App\Filament\Resources\KartukeluargaResource;
use App\Filament\Resources\PendudukResource;
use App\Models\Bantuan;
use App\Models\Bantuanable;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Illuminate\Database\Eloquent\Model;
use Nette\Utils\Finder;

class CreateKartukeluarga extends CreateRecord
{

    use HasWizard;

    protected static string $resource = KartukeluargaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([
                Wizard::make($this->getSteps())
                    ->startOnStep($this->getStartStep())
                    ->cancelAction($this->getCancelFormAction())
                    ->submitAction($this->getSubmitFormAction())
                    ->skippable($this->hasSkippableSteps())
                    ->contained(false)
                    ->persistStepInQueryString('step'),
            ])

            ->columns(null);
    }

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     return dump($data);
    // }

    protected function handleRecordCreation(array $data): Model
    {
        $kartuKeluarga = new KartuKeluarga([
            'kk_id' => $data['kk_id'],
            'wilayah_id' => $data['wilayah_id'],
            'kk_alamat' => $data['kk_alamat'],
            'kk_kepala' => null,
        ]);

        $kk_kepala = new Penduduk([
            'nik' => $data['nik'],
            'kk_id' => $data['kk_id'],
            'nama' => $data['nama_lengkap'],
            'alamat' => $data['alamat'],
            'agama' => $data['agama'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'tempat_lahir' => $data['tempat_lahir'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'golongan_darah' => $data['golongan_darah'],
            'etnis_suku' => $data['etnis_suku'] ?? null,
            'pendidikan' => $data['pendidikan'],
            'status_perkawinan' => $data['status_perkawinan'],
            'pekerjaan' => $data['pekerjaan'],
            'kewarganegaraan' => $data['kewarganegaraan'],
            'ayah' => $data['ayah'] ?? null,
            'ibu' => $data['ibu'] ?? null,
            'status' => $data['status'],
            'status_tempat_tinggal' => $data['status_tempat_tinggal'] ?? null,
            'status_hubungan' => $data['status_hubungan'] ?? null,
            'email' => $data['email'] ?? null,
            'telepon' => $data['telepon'] ?? null,
        ]);

        $bantuan = Bantuan::find($data['bantuans']);

        $bantuan->penduduks()->sync($kk_kepala);

        $cek = $bantuan->penduduks()->get();
        dd($cek);

        $cek = Bantuanable::with('bantuanable')->get();

        dd($cek);

        $cek->map(function ($item) {
            return [
                'bantuan_id' => $item->bantuan_id,
                'bantuanable_id' => $item->bantuanable_id,
                'bantuanable_type' => $item->bantuanable_type,
                'bantuanable' => $item->bantuanable,
            ];
        });

        dd($cek);



        dd(collect($cek));

        $kartuKeluarga->kepalaKeluarga()->associate($kk_kepala);



        dd($kartuKeluarga);
        return dump($data);
    }




    protected function getSteps(): array
    {
        return [
            Step::make('KartuKeluarga')
                ->label('Informasi Kartu Keluarga')
                ->key('kartukeluarga')
                ->schema([
                    Section::make()
                        ->heading('Informasi Kartu Keluarga')
                        ->schema(KartukeluargaResource::getKartuKeluargaFormSchema())->columnSpanFull(),
                    Section::make()
                        ->heading('Informasi Kepala Keluarga')
                        ->collapsible()
                        ->schema(PendudukResource::getPendudukFormSchema())->columnSpanFull(),

                ]),

            Step::make('AnggotaKeluarga')
                ->label('Anggota Keluarga')
                ->key('anggotakeluarga')
                ->schema([
                    Section::make()
                        ->schema([
                            Placeholder::make('Anggota Keluarga')
                                ->content(fn (KartuKeluarga $kartuKeluarga) => $kartuKeluarga),
                            KartukeluargaResource::getAnggotaKeluargaFormSchema(),
                        ]),
                ]),
        ];
    }
}