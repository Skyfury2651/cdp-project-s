<?php

namespace App\Http\Controllers;

use App\Enums\LakeJobProcessType;
use App\Enums\LakeJobStatus;
use App\Http\Requests\ImportRequest;
use App\Http\Requests\ProcessRequest;
use App\Jobs\ProcessLakeData;
use App\Jobs\ValidateLakeData;
use App\Models\LakeJob;
use App\Services\LakeJobService;
use App\Services\LakeService;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function __construct(
        protected LakeService $lakeService,
        protected LakeJobService $lakeJobService
    ) {
    }

    public function import(ImportRequest $request)
    {
        $validatedRequest = $request->validationData();

        $file = resource_path('templates/dms.xlsx');
        // TODO: lay file tu request
        // $file = $validatedRequest['file'];

        dispatch(new ValidateLakeData($validatedRequest['fields'], $file));

        return 'validating';
    }

    public function process(ProcessRequest $request, LakeJob $lakeJob)
    {
        $validatedRequest = $request->validationData();

        if ($validatedRequest['type'] === LakeJobProcessType::STOP) {
            $this->lakeJobService->update($lakeJob['id'], [
                'status' => LakeJobStatus::STOP
            ]);
            return 'stopped';
        }

        dispatch(new ProcessLakeData($lakeJob));
        return 'processing';
    }
}
