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
        Schema::create('move_headers', function (Blueprint $table) {
            $table->id();
            $table->string('from_shop_id');
            $table->string('to_shop_id');
            $table->integer('user_id');
            $table->date('move_date');
            $table->string('memo')->nullable();
            $table->tinyInteger('status_id')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('move_headers');
    }
};
