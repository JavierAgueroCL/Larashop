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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('address_type', ['shipping', 'billing', 'both'])->default('shipping');
            $table->string('alias')->nullable()->comment('Friendly name: My Home, Work, etc.');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('rut')->nullable();
            $table->string('company')->nullable(); // Used for "Empresa" / "Razón Social"
            $table->string('business_activity')->nullable(); // "Giro"
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            
            // Chilean Geography
            $table->foreignId('region_id')->nullable()->constrained('regiones')->nullOnDelete();
            $table->foreignId('comuna_id')->nullable()->constrained('comunas')->nullOnDelete();
            
            // Postal code removed as per previous request
            $table->string('country_code', 2);
            $table->string('phone');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};