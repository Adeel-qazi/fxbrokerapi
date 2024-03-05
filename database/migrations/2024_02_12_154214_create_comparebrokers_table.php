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
        Schema::create('comparebrokers', function (Blueprint $table) {
            $table->id();
            $table->string('brokername');
            $table->json('country');
            $table->string('url');
            $table->string('lose');
            $table->decimal('score', 3, 1);
            $table->string('available');
            $table->string('popularity')->nullable();
            $table->string('updated');
            $table->string('img');
            $table->json('tradingfees');
            $table->json('nontradingfees');
            $table->json('safety');
            $table->json('depositandwithdrawal');
            $table->json('platformandexperience');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comparebrokers');
    }
};
