<?php

namespace App\Imports;

use App\Models\LakeJob;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class LakeCollectionImport implements ToCollection, WithChunkReading, WithValidation, WithHeadingRow, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $attributeRules = [
        'status_bill' => ['required', 'string'],
        'id_bill' => ['required', 'string'],
        'id_bill_taken' => ['required', 'string'],
        'bill_order_time' => ['required', 'date_format:d/m/Y'],
        'bill_delivery_time' => ['required', 'date_format:d/m/Y'],
        'bill_group' => ['required', 'string'],
        'carer_code' => ['required', 'string'],
        'order_name' => ['required', 'string'],
        'line_code' => ['nullable', 'string'], //TODO: line_code hien tai dang chi co data null
        'sold_time' => ['required', 'date_format:d/m/Y'],
        'seller_name' => ['required', 'string'],
        'customer_code' => ['required', 'string'],
        'customer_name' => ['required', 'string'],
        'customer_group' => ['required', 'string'],
        'customer_type' => ['required', 'string'],
        'customer_address' => ['required', 'string'],
        'customer_phone' => ['required', 'string'],
        'customer_description' => ['required', 'string'],
        'warehouse_code' => ['required', 'string'],
        'product_code' => ['required', 'string'],
        'product_name' => ['required', 'string'],
        'unit' => ['required', 'string'],
        'quantity' => ['required', 'integer'],
        'price' => ['required', 'numeric'],
        'amount' => ['required', 'numeric'],
        'vat_percent' => ['required', 'numeric'],
        'vat_number' => ['required', 'numeric'],
        'rebate' => ['required', 'numeric'],
        'bill_total' => ['required', 'numeric'],
        'tax_code' => ['required', 'string'],
        'channel' => ['required', 'string'],
        'data_source' => ['nullable', 'string'], // TODO: this too
        'special_note' => ['nullable', 'string']
    ];

    private $importedRows = 0;
    private $fileCount = 0;
    private $fieldRules = [];
    private $fieldMaps = [];

    public function __construct(protected $customFields, protected LakeJob $lakeJob)
    {
        $this->handleFields();
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        if ($rows->count() <= 0) return;

        ++$this->fileCount;
        $collection = collect([]);

        foreach ($rows as $row) {
            ++$this->importedRows;
            $transformRow = $row->mapWithKeys(function ($item, $key) {
                $newKey = $this->fieldMaps[$key] ?? $key;
                return [$newKey => $item];
            })->filter(function ($value, $key) {
                return in_array($key, array_keys($this->attributeRules));
            });;

            $collection->push($transformRow->toArray());
        }

        $filePath = 'lake/' . $this->lakeJob['title'] . '/' . $this->fileCount . '.json';
        Storage::disk('local')->put($filePath, $collection->toJson());
    }


    public function rules(): array
    {
        return $this->fieldRules;
    }


    public function chunkSize(): int
    {
        return 200;
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

    private function handleFields()
    {
        $fieldMaps = collect([]);
        $this->fieldRules = collect($this->customFields)->mapWithKeys(function ($item) use ($fieldMaps) {
            $header = $this->vnToStr($item['header']);
            $key = '*.' . $header;

            $fieldMaps[$header] = $item['name'];
            $validateConditions = collect($this->attributeRules[$item['name']])->map(function($item) {
                return $item == 'required' ? 'nullable' : $item;
            });

            return [$key => $validateConditions];
        })->toArray();

        $this->fieldMaps = $fieldMaps;
    }

    public function getImportedRowCount(): int
    {
        return $this->importedRows;
    }

    public function getTotalFileCount(): int
    {
        return $this->fileCount;
    }
}
