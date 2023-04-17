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
        Schema::create('lake_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lake_id');
            $table->string('name');
            $table->string('header');
            $table->longText('value');
            $table->timestamps();

            $table->foreign('lake_id')->references('id')->on('lakes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lake_attributes');
    }
};
