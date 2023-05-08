<?php

namespace App\Jobs;

use App\Services\LakeJobService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ValidateLakeData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fields;
    protected $importFile;


    /**
     * Create a new job instance.
     */
    public function __construct($fields, $importFile)
    {
        $this->importFile = $importFile;
        $this->fields = $fields;
    }

    /**
     * Execute the job.
     */
    public function handle(LakeJobService $lakeJobService): void
    {
        $result = $lakeJobService->validate($this->fields, $this->importFile);

        if (!$result['failures']->count() > 0) {
            dispatch(new ProcessLakeData($result['lakeJob']));
        }
    }
}
