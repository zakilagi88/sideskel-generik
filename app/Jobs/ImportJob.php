<?php

namespace App\Jobs;

use App\Imports\Import;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public $uploadFile;

    /**
     * Create a new job instance.
     */
    public function __construct($uploadFile)
    {
        $this->uploadFile = $uploadFile;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Excel::import(new Import, $this->uploadFile);
    }
}