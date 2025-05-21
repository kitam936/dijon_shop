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
        Schema::create('pos_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_header_id')->constrained()->onDelete('cascade');
            $table->integer('user_id');
            $table->string('sku_id');
            $table->integer('price');
            $table->integer('pcs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_details');
    }
};
