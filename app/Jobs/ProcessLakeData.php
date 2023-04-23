<?php

namespace App\Jobs;

use App\Enums\LakeJobStatus;
use App\Models\LakeJob;
use App\Services\LakeJobService;
use App\Services\LakeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessLakeData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected LakeJob $lakeJob)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(LakeJobService $lakeJobService, LakeService $lakeService): void
    {
        $finalStatus = LakeJobStatus::ERROR;
        try {
            $lakeJobService->update($this->lakeJob['id'], [
                'status' => LakeJobStatus::PROCESSING
            ]);

            $lakeService->process($this->lakeJob);

            $finalStatus = LakeJobStatus::SUCCESS;
        } catch (\Throwable $th) {
            //throw $th;
        } finally {
            $lakeJobService->update($this->lakeJob['id'], [
                'status' => $finalStatus
            ]);
        }
    }
}
