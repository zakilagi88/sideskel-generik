<?php

namespace App\Jobs;

use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InsertsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    protected $kkChunks;
    protected $pddChunks;

    /**
     * Create a new job instance.
     */
    public function __construct($kkChunks, $pddChunks)
    {
        $this->kkChunks = $kkChunks;
        $this->pddChunks = $pddChunks;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $kkCount = 0;
        $pddCount = 0;

        try {
            DB::beginTransaction();

            KartuKeluarga::disableAuditing();
            Penduduk::disableAuditing();

            KartuKeluarga::query()->getConnection()->statement('SET FOREIGN_KEY_CHECKS =0;');

            foreach ($this->kkChunks as $chunk) {
                KartuKeluarga::insert($chunk->toArray());
                $kkCount += count($chunk);
            }

            foreach ($this->pddChunks as $chunk) {
                Penduduk::insert($chunk->toArray());
                $pddCount += count($chunk);
            }

            KartuKeluarga::query()->getConnection()->statement('SET FOREIGN_KEY_CHECKS=1;');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error importing data: " . $e->getMessage());
        } finally {
            // if ($success) {
            //     $notification = Notification::make()
            //         ->success()
            //         ->title('Import Data Keluarga Berhasil')
            //         ->icon('fas-check')
            //         ->body("Sebanyak $kkCount data kartu keluarga dan $pddCount data penduduk berhasil diimport.");
            // } else {
            //     $notification = Notification::make()
            //         ->danger()
            //         ->title('Import Data Keluarga Gagal')
            //         ->icon('fas-times')
            //         ->body("Terjadi kesalahan saat mengimport data keluarga.");
            // }

            // $notification->sendToDatabase(User::role('Admin')->get())->send();
            KartuKeluarga::enableAuditing();
            Penduduk::enableAuditing();
        }
    }
}
