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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')
            ->constrained();
            $table->foreignId('user_id')
            ->constrained();
            $table->string('image1')
            ->nullable();
            $table->string('image2')
            ->nullable();

            $table->string('image3')
            ->nullable();

            $table->string('image4')
            ->nullable();

            $table->text('report');
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
        Schema::dropIfExists('reports');
    }
};
