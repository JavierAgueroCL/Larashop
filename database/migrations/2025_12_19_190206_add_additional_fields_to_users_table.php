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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name', 100)->after('name');
            $table->string('last_name', 100)->after('first_name');
            $table->string('phone', 20)->nullable()->after('last_name');
            $table->boolean('is_guest')->default(false)->after('phone');
            $table->foreignId('customer_group_id')->nullable()->after('is_guest')->constrained()->nullOnDelete();
            $table->timestamp('last_login_at')->nullable()->after('customer_group_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['customer_group_id']);
            $table->dropColumn([
                'first_name',
                'last_name',
                'phone',
                'is_guest',
                'customer_group_id',
                'last_login_at'
            ]);
        });
    }
};
