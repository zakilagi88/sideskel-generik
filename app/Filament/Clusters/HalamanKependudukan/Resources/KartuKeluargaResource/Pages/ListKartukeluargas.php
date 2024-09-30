<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\KartuKeluargaResource\Pages;

use App\Exports\TemplateImport;
use App\Filament\Clusters\HalamanKependudukan\Resources\KartuKeluargaResource;
use App\Imports\Import;
use App\Imports\Importer;
use App\Imports\KartuKeluargaImport;
use App\Imports\KartuKeluargaImportExcel;
use App\Jobs\ImportJob;
use App\Jobs\NotifyJob;
use App\Models\{Import as ModelsImport, KartuKeluarga, Penduduk};
use Filament\Actions;
use Filament\Actions\Action as ActionsAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\{FileUpload};
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\Alignment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Facades\Excel;

class ListKartukeluargas extends ListRecords
{
    protected static string $resource = KartukeluargaResource::class;

    public $file;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\Action::make('testing')
            //     ->openUrlInNewTab(true)
            //     ->label('Download Data Testing')
            //     ->size(ActionSize::Small)
            //     ->color('info')
            //     ->button()
            //     ->action(
            //         function () {
            //             return response()->download('test/datatesting.xlsx');
            //         }
            //     ),

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
                        ->rules([
                            'required',
                            'mimes:xlsx,xls,csv',
                        ])
                        ->openable()
                        ->preserveFilenames()
                        ->storeFiles(false)
                        ->storeFileNamesIn('imported')
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

                        /** @var \App\Models\User */
                        $authUser = Filament::auth()->user();

                        Notification::make()
                            ->icon('fas-stopwatch')
                            ->iconColor('primary')
                            ->title('Import Data Kartu Keluarga')
                            ->body('Data Keluarga sedang diimport, silahkan tunggu beberapa saat.')
                            ->persistent()
                            ->send();

                        $import = ModelsImport::create([
                            'imported_by' => $authUser->id,
                            'file_name' => $data['file']->getClientOriginalName(),
                            'status' => 'PROCESSING',
                            'process_rows' => 0,
                            'success_rows' => 0,
                            'related_rows' => 0,
                        ]);

                        $imports = new KartuKeluargaImportExcel($authUser, $import->id);
                        $imports->queue($data['file'])->chain([
                            new NotifyJob($authUser, $import),
                        ]);

                        return redirect()->route('filament.panel.pages.dashboard');
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
}
