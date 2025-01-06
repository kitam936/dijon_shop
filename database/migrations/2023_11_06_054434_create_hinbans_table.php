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
        Schema::create('hinbans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained();
            $table->foreignId('unit_id');
            $table->integer('year_code');
            $table->integer('shohin_gun')->nullable();
            $table->string('hinban_name');
            $table->integer('m_price');
            $table->integer('price');
            $table->integer('cost');
            $table->string('hinban_image')
            ->nullable();
            $table->text('hinban_info')->nullable();
            $table->integer('vendor_id');
            $table->string('face')->nullable();
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
        Schema::dropIfExists('hinbans');
    }
};
