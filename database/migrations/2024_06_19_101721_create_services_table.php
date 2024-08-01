<?php

use App\Models\Service;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title_it');
            $table->string('title_en')->nullable();
            $table->string('subtitle_it');
            $table->string('subtitle_en')->nullable();
            $table->string('subtitleSec_it')->nullable();
            $table->string('subtitleSec_en')->nullable();
            $table->string('abstract_it')->nullable();
            $table->string('abstract_en')->nullable();
            $table->text('body_it');
            $table->text('body_en')->nullable();
            $table->string('links')->nullable();
            $table->text('condition_it')->nullable();
            $table->text('condition_en')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
