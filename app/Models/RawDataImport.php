<?php

namespace App\Imports;

use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RawDataImport implements ToModel, WithHeadingRow
{
    private array $row;
    private array $params;
    private array $validRows = [];
    private array $errorRows = [];

    /**
     * @return array
     */
    public function getValidRows(): array
    {
        return $this->validRows;
    }

    /**
     * @return array
     */
    public function getErrorRows(): array
    {
        return $this->errorRows;
    }

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    // TODO:: check throw location error
    public function newError($message, $fromCol = false) {
        $locationError = '';

        return [
            'message' => $message,
            'location' => $fromCol
        ];
    }

    private $dateColumns = ['bill_order_time'];

    public function formatData($key, $value)
    {
        if (in_array($key, $this->dateColumns)) {
            return Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d h:i:s');
        }

        return $value;
    }

    public function checkRow($key, $value)
    {
        $currentRow = $this->row;
        if (!isset($currentRow[$value])) {
            $errorRows = $this->newError('Cột này không tồn tại', true);
            return '';
        }

        if (!$key || !$value) return '';

        return $this->formatData($key, $currentRow[$value]);
    }

    public function model(array $row)
    {
        $this->row = $row;
        $mappingKey = collect($this->params)->mapWithKeys(function ($value, $key) {
            return [$key => $this->vnToStr($value)];
        });

        $item = [];
        foreach ($mappingKey as $key => $value) {
            $item[$key] = $this->checkRow($key, $value);
        }
        array_push($this->validRows, $item);
    }

    function vnToStr($str)
    {
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => 'Đ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);

        }
        $str = str_replace(' ', '_', $str);

        return strtolower($str);
    }
}
