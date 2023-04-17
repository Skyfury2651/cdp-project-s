<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportRequest;
use App\Imports\LakeCollectionImport;
use App\Imports\LakeImport;
use App\Jobs\ProcessLakeData;
use App\Jobs\ValidateLakeData;
use App\Models\Lake;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function import(ImportRequest $request)
    {
        try {
            $validatedRequest = $request->validationData();

            $file = resource_path('templates/test.xlsx');
            // $file = resource_path('templates/example.xlsx');
            // $file = resource_path('templates/example-short.xlsx');

            $import = new LakeCollectionImport($validatedRequest['fields']); // Validate only
            $import->import($file);

            // $validatedRequest = $request->validationData();
            // $import = new LakeImport($validatedRequest['fields']); // Start import
            // $import->import($file);
            dd(111);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            foreach ($failures as $failure) {
                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.
            }

            dd($failures);
        }
    }

    public function validateData(ImportRequest $request)
    {
        // TODO: pass request data to validate data
        dispatch(new ValidateLakeData);

        echo('validating');
    }

    public function processData(Request $request)
    {
        // TODO: call when lake job status is VALIDATED_ERROR

        // TODO: Get data from file
        dispatch(new ProcessLakeData);

        echo('processing');
    }
}
