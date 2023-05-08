<?php
namespace App\Services;

use App\Enums\LakeJobStatus;
use App\Imports\LakeCollectionImport;
use App\Models\LakeJob;

class LakeJobService
{
    public function create()
    {
        return LakeJob::create([
            'title' => 'job-' . now()->getTimestampMs(),
            'status' => LakeJobStatus::VALIDATING
        ]);
    }

    public function update($id, $data)
    {
        return LakeJob::where('id', $id)->update($data);
    }

    public function validate($fields, $file)
    {
        // Create LakeJob
        $lakeJob = $this->create();

        // Validate data from excel file
        $import = new LakeCollectionImport($fields, $lakeJob);
        $import->import($file);

        // Collect failures
        $failures = collect([]);
        foreach ($import->failures() as $failure) {
            $failures->push([
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
            ]);
        }

        $result = [
            'lakeJob' => $lakeJob,
            'success' => $import->getImportedRowCount(),
            'failures' => $failures,
            'fileCount' => $import->getTotalFileCount(),
        ];

        $isHasErrors = $result['failures']->count() > 0;

        // Update status LakeJob
        $this->update($lakeJob['id'], [
            'file_count' => $result['fileCount'],
            'failures' => $isHasErrors ? $result['failures']->toArray() : null,
            'status' => $isHasErrors ? LakeJobStatus::VALIDATED_ERROR : LakeJobStatus::VALIDATED_SUCCESS
        ]);

        // TODO:: add notification to user

        return $result;
    }
}
