<?php

namespace App\Jobs;

use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\User;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Notifications\Notification;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportEvents\HandlesEvents;
use Throwable;

class InsertsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    protected $kkChunks;
    protected $pddChunks;
    protected $authUser;

    /**
     * Create a new job instance.
     */
    public function __construct($kkChunks, $pddChunks, $authUser)
    {
        $this->kkChunks = $kkChunks;
        $this->pddChunks = $pddChunks;
        $this->authUser = $authUser;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $kkCount = 0;
        $pddCount = 0;

        /** @var \App\Models\User */
        $authUser = User::find($this->authUser);

        DB::beginTransaction();

        KartuKeluarga::disableAuditing();
        Penduduk::disableAuditing();

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        foreach ($this->kkChunks as $chunk) {
            KartuKeluarga::insert($chunk->toArray());
            $kkCount += count($chunk);
        }

        foreach ($this->pddChunks as $chunk) {
            Penduduk::insert($chunk->toArray());
            $pddCount += count($chunk);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Notification::make()
            ->title('Import Selesai')
            ->body("Kartu Keluarga: {$kkCount} data berhasil diimpor. Penduduk: {$pddCount} data berhasil diimpor.")
            ->icon('fas-check')
            ->persistent()
            ->success()
            ->broadcast($authUser)
            ->sendToDatabase($authUser);

        event(new DatabaseNotificationsSent($authUser));

        DB::commit();

        KartuKeluarga::enableAuditing();
        Penduduk::enableAuditing();
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        /** @var \App\Models\User */
        $authUser = User::find($this->authUser);

        Notification::make()
            ->title('Import Gagal')
            ->body("Terjadi kesalahan saat mengimpor data. Silahkan coba lagi. Error: {$exception->getMessage()}")
            ->icon('fas-check')
            ->persistent()
            ->danger()
            ->broadcast($authUser)
            ->sendToDatabase($authUser);

        event(new DatabaseNotificationsSent($authUser));

        DB::rollBack();

        Log::error("Error importing data: " . $exception->getMessage());
    }
}