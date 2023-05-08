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

            // TODO:: check nullable possible
            $table->string('status_bill')->nullable();//Trạng thái
            $table->string('id_bill')->nullable();//Mã phiếu
            $table->string('id_bill_taken')->nullable();//Mã phiếu đặt
            $table->dateTime('bill_order_time')->nullable();//Ngày đặt
            $table->dateTime('bill_delivery_time')->nullable();//Ngày giao
            $table->string('bill_group')->nullable();//Nhóm hoá đơn theo vùng
            $table->string('carer_code')->nullable();//Mã nhân viên phụ trách
            $table->string('order_name')->nullable();//Tên nhân viên đặt
            $table->string('line_code')->nullable();//Mã tuyến
            $table->dateTime('sold_time')->nullable();//Ngày bán
            $table->string('seller_name')->nullable();//Tên người bán
            $table->string('customer_code')->nullable();//Mã khách hàng
            $table->string('customer_name')->nullable();//Tên khách hàng
            $table->string('customer_group')->nullable();//Nhóm khách hàng - table
            $table->string('customer_type')->nullable();//Loại khách hàng
            $table->text('customer_address')->nullable();//Địa chỉ
            $table->string('customer_phone')->nullable();//SĐT
            $table->text('customer_description')->nullable();//Diễn giải
            $table->string('warehouse_code')->nullable();//Mã kho
            $table->string('product_code')->nullable();//Mã sản phẩm - table
            $table->string('product_name')->nullable();//Tên sản phẩm
            $table->string('unit')->nullable();//Đơn vị tính
            $table->integer('quantity')->nullable();//Số lượng
            $table->double('price')->nullable();//Đơn giá
            $table->double('amount')->nullable();//Thành tiền
            $table->double('vat_percent')->nullable();//VAT
            $table->double('vat_number')->nullable();//Tiền thuế
            $table->double('rebate')->nullable();//Chiết khấu
            $table->double('bill_total')->nullable();//Thành tiền tổng
            $table->text('tax_code')->nullable();//Mã số thuế
            $table->text('channel')->nullable();//Kênh
            $table->text('data_source')->nullable();
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
