<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Carrier;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Carrier::firstOrCreate(
            ['name' => 'Starken'],
            [
                'display_name' => 'Starken',
                'delay' => '2-5 días hábiles',
                'is_active' => true,
                'position' => 1,
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Carrier::where('name', 'Starken')->delete();
    }
};