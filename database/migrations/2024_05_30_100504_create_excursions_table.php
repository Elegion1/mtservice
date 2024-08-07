<?php

use App\Models\Excursion;
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
        Schema::create('excursions', function (Blueprint $table) {
            $table->id();
            $table->string('name_it');
            $table->string('name_en')->nullable();
            $table->string('price_increment');
            $table->string('price');
            $table->string('abstract_it')->nullable();
            $table->string('abstract_en')->nullable();
            $table->text('description_it');
            $table->text('description_en')->nullable();
            $table->string('duration');
            $table->timestamps();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excursions');
    }
};
