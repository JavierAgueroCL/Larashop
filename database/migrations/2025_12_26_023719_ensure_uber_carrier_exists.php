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
            ['name' => 'Uber'],
            [
                'display_name' => 'Uber Flash',
                'delay' => '1-3 días hábiles',
                'is_active' => true,
                'position' => 3,
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Carrier::where('name', 'Uber')->delete();
    }
};