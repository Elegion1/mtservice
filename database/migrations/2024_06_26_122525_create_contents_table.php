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
        Schema::create('contents', function (Blueprint $table) {
            $table->id();

            $table->string('title_it')->nullable();
            $table->string('title_en')->nullable();
            $table->text('subtitle_it')->nullable();
            $table->text('subtitle_en')->nullable();
            $table->text('body_it')->nullable();
            $table->text('body_en')->nullable();
            $table->string('links')->nullable();
            $table->string('order')->nullable();
            $table->boolean('show')->default(0);

            $table->unsignedBigInteger('page_id')->nullable();
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
