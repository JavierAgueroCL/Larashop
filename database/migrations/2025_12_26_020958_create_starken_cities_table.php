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
        Schema::create('starken_cities', function (Blueprint $table) {
            $table->id();
            $table->integer('region_code');
            $table->integer('city_code');
            $table->string('city_name');
            $table->integer('comuna_code')->index(); // Index for faster lookup by comuna
            $table->string('comuna_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('starken_cities');
    }
};