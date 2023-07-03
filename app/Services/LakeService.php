<?php
namespace App\Services;

use App\Imports\LakeCollectionImport;
use App\Models\LakeJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LakeService
{
    public function process (LakeJob $lakeJob)
    {
        $filePathPrefix = 'lake/' . $lakeJob['title'] . '/';

        for ($fileIndex=1; $fileIndex <= $lakeJob['file_count']; $fileIndex++) {
            $filePath = $filePathPrefix . $fileIndex . '.json';
            $file = Storage::disk('local')->get($filePath);

            $data = collect(json_decode($file, true))->map(function ($row) {
                $modifiedRow = collect($row)->mapWithKeys(function ($item, $key) {
                    $dateFields = collect(['bill_order_time', 'bill_delivery_time', 'sold_time']);
                    if ($dateFields->contains($key)) {
                        return [$key => Carbon::createFromFormat('d/m/Y', $item)];
                    }
                    return [$key => $item];
                });

                // TODO: them data cho line_code vaf data_source
                $modifiedRow['line_code'] = 'DMS';
                $modifiedRow['data_source'] = 'DMS';

                return $modifiedRow->toArray();
            })->toArray();

            $lakeJob->lakes()->createMany($data);
        }
    }
}
