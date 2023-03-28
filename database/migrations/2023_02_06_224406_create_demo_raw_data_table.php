<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demo_raw_data', function (Blueprint $table) {
            $table->id();
            $table->string('carer_code')->nullable(); // Mã nhân viên phụ trách
            $table->string('order_name')->nullable(); // Tên nhân viên đặt
            $table->dateTime('bill_order_time')->nullable(); //Ngày đặt
            $table->string('bill_group')->nullable();  // Nhóm
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demo_raw_data');
    }
};
