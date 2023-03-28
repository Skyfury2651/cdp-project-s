<?php

namespace App\Imports;

use App\Models\Lake;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class LakeImport implements SkipsEmptyRows, WithHeadingRow, ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
//        $row['status'] = ($row['status'] == '') ? '1' : $row['Trạng thái đơn'];
//        $row['special_note'] = ($row['special_note'] == '') ? '' : $row['special_note'];
//        dd($row);
//        if(isset($row['kenh']) || isset($row['ma_so_thue']))
//        {
//            $row['gate'] = ($row['gate'] == '') ? '' : '1';//Cổng
//            $row['tax_code'] = ($row['tax_code'] == '') ? '' : $row['ma_so_thue'];//Mã số thuế
//            $row['channel'] = ($row['channel'] == '') ? '' : $row['kenh'];//Mã số thuế
//        }
        $row['ngay_giao'] = ($row['ngay_giao'] == '') ? null : Carbon::createFromFormat('d/m/Y', $row['ngay_giao'])->format('Y-m-d h:i:s');
        $row['ma_phieu'] = ($row['ma_phieu'] == '') ? null : $row['ma_phieu'];
        $row['ma_nhan_vien'] = ($row['ma_nhan_vien'] == '') ? null : $row['ma_nhan_vien'];
        $row['nhan_vien_dat'] = ($row['nhan_vien_dat'] == '') ? null : $row['nhan_vien_dat'];
        $row['tuyen'] = ($row['tuyen'] == '') ? null : $row['tuyen'];
        $row['nhom_khach_hang'] = ($row['nhom_khach_hang'] == '') ? null : $row['nhom_khach_hang'];
        $row['loai_khach_hang'] = ($row['loai_khach_hang'] == '') ? null : $row['loai_khach_hang'];
        $row['dia_chi'] = ($row['dia_chi'] == '' || $row['dia_chi'] == '.') ? null : $row['dia_chi'];
        $row['sdt'] = ($row['sdt'] == '' || $row['sdt'] == '.') ? null : $row['sdt'];
        $row['ten_san_pham'] = ($row['ten_san_pham'] == '') ? null : $row['ten_san_pham'];

        return new Lake([
//            'bill_order_time'=>Date::excelToDateTimeObject($row['ngay_dat'])->format('d/m/Y'),//Ngày đặt
//            'bill_delivery_time'=>Date::excelToDateTimeObject($row['ngay_giao'])->format('d/m/Y'),//Ngày giao
//            'sold_time'=>Date::excelToDateTimeObject($row['ngay_ban'])->format('d/m/Y'),//Ngày bán
            'status_bill' => $row['trang_thai'],//Trạng thái - enum
            'id_bill' => $row['ma_phieu'],//Mã phiếu
            'id_bill_taken' => $row['ma_phieu_dat'],//Mã phiếu đặt
            'bill_order_time' => Carbon::createFromFormat('d/m/Y', $row['ngay_dat'])->format('Y-m-d h:i:s'),
            'bill_delivery_time' => $row['ngay_giao'],
            'bill_group' => $row['nhom'],//Nhóm hoá đơn theo vùng - enum
            'carer_code' => $row['ma_nhan_vien'],//Mã nhân viên phụ trách
            'order_name' => $row['nhan_vien_dat'],//Tên nhân viên đặt
            'line_code' => $row['tuyen'],//Mã tuyến
            'sold_time' => Carbon::createFromFormat('d/m/Y', $row['ngay_ban'])->format('Y-m-d h:i:s'),
            'seller_name' => $row['nguoi_ban'],//Tên người bán
            'customer_code' => $row['ma_khach_hang'],//Mã khách hàng
            'customer_name' => $row['ten_khach_hang'],//Tên khách hàng
            'customer_group' => $row['nhom_khach_hang'],//Nhóm khách hàng - table
            'customer_type' => $row['loai_khach_hang'],//Loại khách hàng
            'customer_address' => $row['dia_chi'],//Địa chỉ
            'customer_phone' => $row['sdt'],//SĐT
            'customer_description' => $row['dien_giai'],//Diễn giải
            'warehoure_code' => $row['ma_kho'],//Mã kho
            'product_code' => $row['ma_san_pham'],//Mã sản phẩm - table
            'product_name' => $row['ten_san_pham'],//Tên sản phẩm
            'unit' => $row['dvt'],//Đơn vị tính - enum
            'numbered' => $row['so_luong'],//Số lượng
            'price' => $row['don_gia'],//Đơn giá
            'amount' => $row['thanh_tien'],//Thành tiền
            'vat_percent' => $row['vat'],//VAT
            'vat_number' => $row['tien_thue'],//Tiền thuế
            'rebate' => $row['chiet_khau'],//Chiết khấu
            'bill_total' => $row['thanh_tien_tong'],//Thành tiền tổng
            'gate' => '0',
        ]);
    }
//    public function headingRow(): int
//    {
//        return 1;
//    }
    public function collection(Collection $collection)
    {
        return collect();
        // TODO: Implement collection() method.
    }
}
