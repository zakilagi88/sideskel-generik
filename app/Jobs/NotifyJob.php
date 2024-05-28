<?php

namespace App\Jobs;

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $kkCount, $pddCount, $authUser;

    /**
     * Create a new job instance.
     */
    public function __construct($kkCount, $pddCount, $authUser)
    {
        $this->kkCount = $kkCount;
        $this->pddCount = $pddCount;
        $this->authUser = $authUser;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Notification::make()
            ->title('Import Selesai')
            ->body("Kartu Keluarga: {$this->kkCount} data berhasil diimpor. Penduduk: {$this->pddCount} data berhasil diimpor.")
            ->icon('fas-check')
            ->persistent()
            ->success()
            ->send();
    }
}
