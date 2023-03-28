<?php

namespace App\Models;

use App\Enums\BillGroup;
use App\Enums\BillStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lake extends Model
{
    use HasFactory;

    protected $guarded = [''];

    public function setBillStatusAttribute($value)
    {
        switch ($value) {
            case 'Đã bán':
                $this->attributes['status_bill'] = 1;
            case 'Đã xuất hàng':
                $this->attributes['status_bill'] = 2;
            case 'Đã giao':
                $this->attributes['status_bill'] = 3;
            default:
                return $value;
        }
    }
    public function getBillStatusAttribute($value)
    {
        switch ($value) {
            case "1":
                $this->attributes['status_bill'] = BillStatus::Sold;
            case "2":
                $this->attributes['status_bill'] = BillStatus::Exported;
            case "3":
                $this->attributes['status_bill'] = BillStatus::Delivered;
            default:
                return $value;
        }
    }
}
