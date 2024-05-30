<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('departure_id');
            $table->unsignedBigInteger('arrival_id');
            $table->string('price');
            $table->string('price_increment');
            $table->string('duration');
            $table->string('distance');

            $table->timestamps();

            $table->foreign('departure_id')->references('id')->on('destinations')->onDelete('cascade');
            $table->foreign('arrival_id')->references('id')->on('destinations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
