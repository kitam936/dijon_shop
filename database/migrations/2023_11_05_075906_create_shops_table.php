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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            // $table->bigInteger('company_id');
            $table->foreignId('company_id')->constrained();
            // ->onUpdate('cascade')
            // ->onDelete('cascade');
            $table->foreignId('area_id')->constrained();
            $table->string('shop_name');
            $table->text('shop_info')->nullable();
            $table->boolean('is_selling') ;
            $table->integer('rate');
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
        Schema::dropIfExists('shops');
    }
};
