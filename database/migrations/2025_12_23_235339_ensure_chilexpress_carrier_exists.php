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
            ['name' => 'Chilexpress'],
            [
                'display_name' => 'Chilexpress',
                'delay' => '1-3 días hábiles',
                'is_active' => true,
                'position' => 2,
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We generally don't delete data in down() for this kind of seed, but optionally:
        // Carrier::where('name', 'Chilexpress')->delete();
    }
};