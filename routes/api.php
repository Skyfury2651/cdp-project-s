<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

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

Route::post('upload-file', function (Request $request) {
    $file = $request->file('file');

    // TODO:: add validate check file is excel else throw error
    if (!$request->file('file')) throw new Exception('Error');

    // Vd: khách muốn giá trị id_bill = Mã phiếu thì $params lấy từ api
    $params = [
        'id_bill' => 'Mã phiếu',
        'id_bill_taken' => 'Mã phiếu đặt'
    ];


    $instance = new \App\Imports\RawDataImport($params);
    $data = Excel::import($instance, $file);
    dd($instance);

//    $collections = Excel::toArray(new \App\Imports\RawDataImport($params),$file,null,\Maatwebsite\Excel\Excel::XLSX);
//    $data = Excel::import(new \App\Imports\RawDataImport($params),$file,null,\Maatwebsite\Excel\Excel::XLSX);
//    dd($data);

// Output expected :

// Case 1  : Không có row lỗi import toàn bộ vô db
// Case 2  : Có row bị lỗi => Trả về các row lỗi và hỏi có muốn import các row bình thường

// Addition : Bổ sung thêm progress bar nếu có thể :/ ( No idea cái này )

    return 'done';
});
