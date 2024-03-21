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
        Schema::create('fee_data', function (Blueprint $table) {
            $table->id();
            $table->string('broker');
            $table->string('lose');
            $table->string('path')->nullable();
            $table->string('link');
            $table->string('image');
            $table->string('type')->nullable();
            $table->json('country');
            $table->json('eurusd');
            $table->json('usdjpy');
            $table->json('gbpusd');
            $table->json('usdcad');
            $table->json('audusd');
            $table->json('nzdusd');
            $table->json('eurjpy');
            $table->json('gbpjpy');
            $table->json('usdchf');
            $table->json('eurgbp');
            $table->json('nzdjpy');   
            $table->json('audjpy');   
            $table->json('gold');   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_data');
    }
};
