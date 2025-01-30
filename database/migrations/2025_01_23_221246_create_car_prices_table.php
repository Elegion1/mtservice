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
        Schema::create('car_prices', function (Blueprint $table) {
            $table->id();

            // Definizione delle chiavi esterne
            $table->unsignedBigInteger('car_id')->nullable();
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('set null');

            $table->unsignedBigInteger('time_period_id');
            $table->foreign('time_period_id')->references('id')->on('time_periods')->onDelete('cascade');

            // Prezzo
            $table->decimal('price', 8, 2);

            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_prices');
    }
};
