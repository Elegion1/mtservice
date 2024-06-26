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
        Schema::create('images', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('service_id')->nullable();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');

            $table->unsignedBigInteger('excursion_id')->nullable();
            $table->foreign('excursion_id')->references('id')->on('excursions')->onDelete('cascade');

            $table->unsignedBigInteger('partner_id')->nullable();
            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade');

            $table->unsignedBigInteger('owner_data_id')->nullable();
            $table->foreign('owner_data_id')->references('id')->on('owner_data')->onDelete('cascade');

            $table->unsignedBigInteger('content_id')->nullable();
            $table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
            
            $table->string('path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
