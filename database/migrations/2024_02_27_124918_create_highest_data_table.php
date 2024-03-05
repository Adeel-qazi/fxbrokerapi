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
        Schema::create('highest_data', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('country');
            $table->string('url');
            $table->decimal('ratting', 3, 1);
            $table->string('lose');
            $table->string('path')->nullable();
            $table->string('min_deposit');
            $table->string('max_leverage');
            $table->text('platform');
            $table->string('broker_img');
            $table->boolean('recommended')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('highest_data');
    }
};
