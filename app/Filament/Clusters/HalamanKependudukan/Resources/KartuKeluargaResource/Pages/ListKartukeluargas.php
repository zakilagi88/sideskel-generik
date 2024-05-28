<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\KartuKeluargaResource\Pages;

use App\Exports\TemplateImport;
use App\Filament\Clusters\HalamanKependudukan\Resources\KartuKeluargaResource;
use App\Imports\Import;
use App\Imports\KartuKeluargaImport;
use App\Jobs\ImportJob;
use App\Models\{KartuKeluarga, Penduduk, User,};
use Filament\Actions;
use Filament\Actions\Action as ActionsAction;
use Filament\Forms\Components\{FileUpload};
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ListKartukeluargas extends ListRecords
{
    protected static string $resource = KartukeluargaResource::class;

    public $file;
    public $batchId;
    public $isImporting = false;
    public $importFilePath;
    public $importProgress = 0;
    public $importFinished = false;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('testing')
                ->openUrlInNewTab(true)
                ->label('Download Data Testing')
                ->size(ActionSize::Small)
                ->color('info')
                ->button()
                ->action(
                    function () {
                        return response()->download('test/datatesting.xlsx');
                    }
                ),

            Actions\CreateAction::make()->label('Tambah Keluarga')
                ->label('Tambah Keluarga')
                ->size(ActionSize::Small)
                ->color('primary')
                ->button(),

            ActionsAction::make('Import')
                ->label('Import Data')
                ->size(ActionSize::Small)
                ->form([
                    FileUpload::make('file')
                        ->label('Upload File')
                        ->required()
                        ->disk('local')
                        ->directory('deskel/imports')
                        ->moveFiles()
                        ->rules([
                            'required',
                            'mimes:xlsx,xls,csv',
                        ])
                        ->openable()
                        ->preserveFilenames()
                        ->acceptedFileTypes(['application/msexcel', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                ])
                // ->extraModalFooterActions([
                //     ActionsAction::make('Download Template')
                //         ->openUrlInNewTab(true)
                //         ->button()
                //         ->action(
                //             function () {
                //                 return Excel::download(new TemplateImport, 'template_imports.xlsx');
                //             }
                //         ),

                // ])
                ->modalFooterActionsAlignment(Alignment::End)
                ->action(
                    function (array $data) {

                        Notification::make()
                            ->icon('fas-stopwatch')
                            ->iconColor('primary')
                            ->title('Import Data Kartu Keluarga')
                            ->body('Data Keluarga sedang diimport, silahkan tunggu beberapa saat.')
                            ->seconds(1)
                            ->send();

                        Excel::import(new Import, $data['file']);


                        // self::import($data['file']);



                        return redirect()->route('filament.admin.pages.dashboard');
                    }

                )
        ];
    }

    protected function tambahKartukeluarga(array $data)
    {
        $kartuKeluarga = KartuKeluarga::create([
            'kk_id' => $data['kk_id'],
            'kk_alamat' => strtoupper($data['kk_alamat']),
            'deskel_id' => $data['deskel_id'],
            'wilayah_id' => $data['wilayah_id'],
        ]);

        return $kartuKeluarga;
    }

    protected function tambahKepalaKeluarga(array $data, KartuKeluarga $kartuKeluarga)
    {
        $kepalaKeluarga = $kartuKeluarga->kepalaKeluarga()->create([
            'nik' => $data['kk_kepala'],
            'hubungan' => 'KEPALA KELUARGA',
        ]);

        return $kepalaKeluarga;
    }

    protected function tambahAnggotaKeluarga(array $data, KartuKeluarga $kartuKeluarga)
    {
        foreach ($data['anggotaKeluarga'] as $anggota) {
            $kartuKeluarga->anggotaKeluarga()->create([
                'nik' => $anggota['nik'],
                'hubungan' => $anggota['hubungan'],
            ]);
        }

        return $kartuKeluarga;
    }

    protected function assosiasiKepalaKeluarga(array $data, KartuKeluarga $kartuKeluarga)
    {
        $penduduk = Penduduk::where('nik', $data['kk_kepala'])->first();
        $kartuKeluarga->kepalaKeluarga()->associate($penduduk);
        $kartuKeluarga->save();

        return $kartuKeluarga;
    }

    public function downloadTemplate()
    {
        return Excel::download(new TemplateImport, 'template_imports.xlsx');
    }

    public function import($data)
    {
        $this->importFilePath = $data;
        // $this->isImporting = true;
        $batch = Bus::batch([new ImportJob($this->importFilePath)])->dispatch();

        // $this->batchId = $batch->id;
    }

    // public function cancelImport()
    // {
    //     if ($this->batchId) {
    //         Bus::findBatch($this->batchId)->cancel();
    //     }
    // }

    // public function getImportBatchProperty()
    // {
    //     if (!$this->batchId) {
    //         return null;
    //     }
    //     return Bus::findBatch($this->batchId);
    // }


    // public function updateImportProgress()
    // {
    //     if (!$this->importBatch) {
    //         return;
    //     } else {
    //         $this->importProgress = $this->importBatch->progress();
    //         $this->importFinished = $this->importBatch->finished();
    //         if ($this->importFinished) {
    //             Storage::delete($this->importFilePath);
    //             $this->isImporting = false;
    //             $this->importFilePath = null;
    //             $this->batchId = null;
    //         }
    //     }
    // }
}
