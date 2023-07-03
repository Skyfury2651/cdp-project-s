<?php

namespace App\Imports;

use App\Models\Lake;
use App\Models\LakeAttribute;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;

class LakeImport implements ToModel, WithChunkReading, WithValidation, WithHeadingRow, WithBatchInserts
{
    use Importable;

    protected $customFields;
    protected $attributeRules = [
        'status_bill' => ['required', 'string']
    ];

    public function __construct($customFields)
    {
        $this->customFields = $customFields;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if(!array_filter($row)) {
            return null;
        }
        $lake = Lake::create([
            'title' => 'test',
            'description' => 'test too'
        ]);

        $data = [];

        foreach ($this->customFields as $key => $value) {
            $header = $this->vnToStr($value['header']);
            $data[] = [
                'name' => $value['name'],
                'header' => $header,
                'value' => $row[$header],
                'lake_id' => $lake['id']
            ];
        }

        $lake->attributes()->createMany($data);
        return $lake;
    }

    public function rules(): array
    {
        return collect($this->customFields)->flatMap(function ($item) {
            $header = $this->vnToStr($item['header']);
            $key =  '*.' . $header;

            return [$key => $this->attributeRules[$item['name']]];
        })->toArray();
    }


    public function chunkSize(): int
    {
        return 10;
    }

    public function batchSize(): int
    {
        return 10;
    }

    private function vnToStr($str)
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
