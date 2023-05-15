<?php

use App\Http\Controllers\ImportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/import-data', [ImportController::class, 'import']);
Route::post('/process-data/{lakeJob}', [ImportController::class, 'process']);

Route::post('upload-advanced', [\App\Http\Controllers\UploaderController::class, 'uploadFile'])->name('file-upload');
