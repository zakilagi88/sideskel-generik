oh<?php

namespace App\Jobs;

use App\Models\Import;
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

    protected $user, $import;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, Import $import)
    {
        $this->user = $user;
        $this->import = $import;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Pesan sukses
        $successMessage = __('Data Keluarga berhasil diimport.') . '<br>' .
            __('Terdata :kk keluarga baru.', ['kk' => $this->import->related_rows]) . '<br>' .
            __('Ada :pdd_success penduduk baru dari :pdd penduduk yang diimport.', [
                'pdd_success' => $this->import->success_rows,
                'pdd' => $this->import->process_rows
            ]);

        // Mengirim notifikasi berhasil
        $this->user->notify(
            Notification::make()
                ->success()
                ->title('Import Data Kartu Keluarga')
                ->body($successMessage)
                ->persistent()
                ->broadcast($this->user)
                ->toDatabase(),
        );

        $failures = $this->import->failedImports->toArray();
        // Mengirim notifikasi error jika ada kesalahan
        if (!empty($failures)) {
            // Mengelompokkan error berdasarkan attribute dan pesan error
            $groupedErrors = [];
            foreach ($failures as $failure) {
                $errors = json_decode($failure['errors'], true); // Mengubah string JSON ke array
                foreach ($errors as $error) {
                    $attribute = $failure['attribute'];
                    if (!isset($groupedErrors[$attribute])) {
                        $groupedErrors[$attribute] = [];
                    }
                    if (!isset($groupedErrors[$attribute][$error])) {
                        $groupedErrors[$attribute][$error] = [];
                    }
                    $groupedErrors[$attribute][$error][] = $failure['row'];
                }
            }

            // Menyusun pesan notifikasi
            $message = __('Terdapat kesalahan pada data berikut:');
            $message .= '<ul class="list-disc pl-5">';
            foreach ($groupedErrors as $attribute => $errors) {
                foreach ($errors as $error => $rows) {
                    $rowsString = implode(', ', $rows);
                    $message .= "<li class='mb-2 text-red-200 text-uppercase'><span class='font-bold'>{$attribute}</span> - {$error} <span class='font-bold'> <br> Pada baris ke-{$rowsString}</span></li>";
                }
            }
            $message .= '</ul>';

            // Mengirim notifikasi error
            $this->user->notify(
                Notification::make()
                    ->danger()
                    ->title('Import Data Kartu Keluarga')
                    ->body($message)
                    ->persistent()
                    ->broadcast($this->user)
                    ->toDatabase(),
            );
        }
    }
}