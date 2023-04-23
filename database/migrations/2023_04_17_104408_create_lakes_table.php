<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lakes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lake_job_id');

            $table->string('status_bill');//Trạng thái
            $table->string('id_bill');//Mã phiếu
            $table->string('id_bill_taken');//Mã phiếu đặt
            $table->dateTime('bill_order_time');//Ngày đặt
            $table->dateTime('bill_delivery_time');//Ngày giao
            $table->string('bill_group');//Nhóm hoá đơn theo vùng
            $table->string('carer_code');//Mã nhân viên phụ trách
            $table->string('order_name');//Tên nhân viên đặt
            $table->string('line_code');//Mã tuyến
            $table->dateTime('sold_time');//Ngày bán
            $table->string('seller_name');//Tên người bán
            $table->string('customer_code');//Mã khách hàng
            $table->string('customer_name');//Tên khách hàng
            $table->string('customer_group');//Nhóm khách hàng - table
            $table->string('customer_type');//Loại khách hàng
            $table->text('customer_address');//Địa chỉ
            $table->string('customer_phone');//SĐT
            $table->text('customer_description');//Diễn giải
            $table->string('warehouse_code');//Mã kho
            $table->string('product_code');//Mã sản phẩm - table
            $table->string('product_name');//Tên sản phẩm
            $table->string('unit');//Đơn vị tính
            $table->integer('quantity');//Số lượng
            $table->double('price');//Đơn giá
            $table->double('amount');//Thành tiền
            $table->double('vat_percent');//VAT
            $table->double('vat_number');//Tiền thuế
            $table->double('rebate');//Chiết khấu
            $table->double('bill_total');//Thành tiền tổng
            $table->text('tax_code');//Mã số thuế
            $table->text('channel');//Kênh
            $table->text('data_source');
            $table->text('special_note')->nullable();//có các thay đổi đặc biệt
            $table->timestamps();

            $table->foreign('lake_job_id')->references('id')->on('lake_jobs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lakes');
    }
};
