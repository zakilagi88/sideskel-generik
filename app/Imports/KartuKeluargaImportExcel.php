<?php

namespace App\Imports;

use App\Enums\Kependudukan\{AgamaType, EtnisSukuType, GolonganDarahType, JenisKelaminType, KewarganegaraanType, PekerjaanType, PendidikanType, PerkawinanType, StatusHubunganType,};
use App\Facades\Deskel;
use App\Models\Import;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\User;
use App\Models\Wilayah;
use App\Settings\GeneralSettings;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\{Importable, SkipsOnFailure, SkipsOnError, ToCollection, WithChunkReading, WithEvents, WithHeadingRow, WithMultipleSheets, WithStartRow, WithValidation};
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Validators\Failure;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class KartuKeluargaImportExcel implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue, SkipsOnFailure, SkipsOnError, WithValidation, WithEvents, WithMultipleSheets, WithStartRow
{

    use Importable;

    private $wilayah, $deskel, $settings, $importedBy, $importId;
    private $cacheKeyKk, $cacheKeyPdd;

    public function sheets(): array
    {
        return [
            'DATABASE' => $this,
        ];
    }

    public function startRow(): int
    {
        return 4;
    }

    public function headingRow(): int
    {
        return 3;
    }

    public function __construct(User $importedBy, int $importId)
    {
        $this->importedBy = $importedBy;
        $this->importId = $importId;
        $this->deskel = Deskel::getFacadeRoot();
        $this->wilayah = Wilayah::tree()->get();
        $this->settings = app(GeneralSettings::class)->toArray();

        $this->cacheKeyKk = 'import_' . $this->importId . '_kkCount';
        $this->cacheKeyPdd = 'import_' . $this->importId . '_pddCount';

        $depth = match ($this->deskel->struktur) {
            'Khusus' => 0,
            'Dasar' => 1,
            'Lengkap' => 2,
            default => null,
        };

        $this->wilayah = $depth !== null
            ? $this->wilayah->where('depth', $depth)->pluck('wilayah_nama', 'wilayah_id')->toArray()
            : [];
    }



    public function concatWilayah($parent, $sub_parent = null, $child = null): string
    {
        return match ($this->deskel->struktur) {
            'Khusus' => $this->settings['sebutan_wilayah']['Khusus'][0] . ' ' . $parent,
            'Dasar' => $this->settings['sebutan_wilayah']['Dasar'][1] . ' ' . $child . ' / ' . $this->settings['sebutan_wilayah']['Dasar'][0] . ' ' . $parent,
            'Lengkap' => $this->settings['sebutan_wilayah']['Lengkap'][2] . ' ' . $child . ' / ' . $this->settings['sebutan_wilayah']['Lengkap'][1] . ' ' . $sub_parent . ' / ' . $this->settings['sebutan_wilayah']['Lengkap'][0] . ' ' . $parent,
            default => null,
        };
    }

    public function findWilayahId($concatenated)
    {
        return array_search($concatenated, $this->wilayah) ?: null;
    }


    public function collection(Collection $rows)
    {
        $kkCount = 0;
        $pddCount = 0;
        // proses import data
        foreach ($rows as $row) {
            $concatenated = match ($this->deskel->struktur) {
                'Khusus' => $this->concatWilayah(parent: $row[strtolower($this->settings['sebutan_wilayah']['Khusus'][0])]),
                'Dasar' => $this->concatWilayah(parent: $row[strtolower($this->settings['sebutan_wilayah']['Dasar'][0])], child: $row[strtolower($this->settings['sebutan_wilayah']['Dasar'][1])]),
                'Lengkap' => $this->concatWilayah(parent: $row[strtolower($this->settings['sebutan_wilayah']['Lengkap'][0])], sub_parent: $row[strtolower($this->settings['sebutan_wilayah']['Lengkap'][1])], child: $row[strtolower($this->settings['sebutan_wilayah']['Lengkap'][2])]),
                default => null,
            };

            $kk = KartuKeluarga::firstOrNew(
                ['kk_id' => (string)$row['no_kk']], // Key columns to check
                [
                    'kk_alamat' => (string)$row['alamat_sekarang'],
                    'wilayah_id' => $this->findWilayahId($concatenated),
                    'created_at' => self::formatTanggal($row['tanggal_update_data']) ?? Carbon::now(),
                    'updated_at' => self::formatTanggal($row['tanggal_update_data']) ?? Carbon::now(),
                ]
            );

            if (!$kk->exists) {
                $kk->save();
                $kkCount++;
            }

            Penduduk::updateOrCreate(
                ['nik' => (string)$row['nik']],
                [
                    'kk_id' => (string)$row['no_kk'],
                    'is_nik_sementara' => (empty($row['nik_sementara']) || $row['nik_sementara'] == '-') ? false : true,
                    'jenis_identitas' => $row['jenis_identitas'] ?? 'KTP',
                    'nama_lengkap' => $row['nama_lengkap'],
                    'jenis_kelamin' => $row['jenis_kelamin'] ?? null,
                    'tempat_lahir' => $row['tempat_lahir'] ?? null,
                    'tanggal_lahir' => self::formatTanggal($row['tanggal_lahir']) ?? null,
                    'umur' => Carbon::parse(self::formatTanggal($row['tanggal_lahir']))->age ?: null,
                    'agama' => $row['agama'] ?? null,
                    'pendidikan' => $row['pendidikan'] ?? null,
                    'pekerjaan' => $row['pekerjaan'] ?? null,
                    'kewarganegaraan' => $row['kewarganegaraan'] ?? 'WNI',
                    'nama_ayah' => $row['nama_ayah'] ?? null,
                    'nama_ibu' => $row['nama_ibu'] ?? null,
                    'nik_ayah' => $row['nik_ayah'] ?? null,
                    'nik_ibu' => $row['nik_ibu'] ?? null,
                    'etnis_suku' => $row['etnis_suku'] ?? null,
                    'golongan_darah' => $row['golongan_darah'] ?? null,
                    'status_penduduk' => $row['status_penduduk'] ?? 'TETAP',
                    'status_pengajuan' => 'DIVERIFIKASI',
                    'status_dasar' => $row['status_dasar'] ?? 'HIDUP',
                    'status_perkawinan' => $row['status_perkawinan'],
                    'status_hubungan' => $row['status_hubungan'],
                    'alamat_sekarang' => $row['alamat_sekarang'],
                    'alamat_sebelumnya' => $row['alamat_sebelumnya'] ?? null,
                    'telepon' => $row['telepon'] ?? null,
                    'email' => $row['email'] ?? null,
                    'created_at' => self::formatTanggal($row['tanggal_update_data']) ?? Carbon::now(),
                    'updated_at' => self::formatTanggal($row['tanggal_update_data']) ?? Carbon::now(),
                ]
            );

            $pddCount++;
        }

        Cache::increment($this->cacheKeyKk, $kkCount);
        Cache::increment($this->cacheKeyPdd, $pddCount);
    }

    public function rules(): array
    {
        return [
            '*.nik' => ['unique:penduduk,nik', 'digits:16'],
            '*.no_kk' => ['digits:16'],
            '*.jenis_kelamin' => [Rule::enum(JenisKelaminType::class)],
            '*.agama' => [Rule::enum(AgamaType::class)],
            '*.pendidikan' => [Rule::enum(PendidikanType::class)],
            '*.pekerjaan' => [Rule::enum(PekerjaanType::class)],
            '*.kewarganegaraan' => [Rule::enum(KewarganegaraanType::class)],
            '*.etnis_suku' => ['nullable', Rule::enum(EtnisSukuType::class)],
            '*.golongan_darah' => ['nullable', Rule::enum(GolonganDarahType::class)],
            '*.status_perkawinan' => [Rule::enum(PerkawinanType::class)],
            '*.status_hubungan' => [Rule::enum(StatusHubunganType::class)],
        ];
    }

    private function formatTanggal($tgl)
    {
        $unixTimestamp = Date::excelToTimestamp($tgl);
        $carbonDate = Carbon::createFromTimestamp($unixTimestamp, 'UTC');
        $formattedDate = $carbonDate->format('Y-m-d H:i:s');
        return $formattedDate;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function onError(\Throwable $e) {}

    /**
     * @param Failure[] $failures
     */
    public function onFailure(Failure ...$failures)
    {

        $data = collect($failures)->map(function (Failure $failure) {
            return [
                'import_id' => $this->importId,
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => json_encode($failure->errors()), // Convert errors array to JSON string
                'values' => json_encode($failure->values()), // Convert values array to JSON string
            ];
        })->toArray();

        // Insert the data into the database
        DB::table('failed_imports')->insert($data);
    }

    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function (ImportFailed $event) {

                $errorMessage = $event->getException()->getMessage();

                $this->importedBy->notify(
                    Notification::make()
                        ->title('Import Gagal')
                        ->body("Import data gagal. Silahkan coba lagi. {$errorMessage}")
                        ->icon('fas-xmark')
                        ->persistent()
                        ->success()
                        ->toBroadcast()

                );
            },

            AfterImport::class => function (AfterImport $event) {

                $reader = $event->getReader();

                $totalKk = Cache::get($this->cacheKeyKk, 0);
                $totalPdd = Cache::get($this->cacheKeyPdd, 0);

                // Simpan total ke database
                Import::where('id', $this->importId)->update([
                    'process_rows' => $reader->getTotalRows()['DATABASE'] - 1,
                    'success_rows' => $totalPdd,
                    'related_rows' => $totalKk,
                ]);

                Cache::forget($this->cacheKeyKk);
                Cache::forget($this->cacheKeyPdd);
            },
        ];
    }
}
