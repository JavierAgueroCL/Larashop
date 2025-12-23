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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email')->unique();
            $table->string('google_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->string('phone', 20)->nullable();
            $table->boolean('is_guest')->default(false);
            // $table->foreignId('customer_group_id')->nullable()->constrained()->nullOnDelete(); // Moving this to separate migration or ensure order. Customer groups migration is 2025_12_19_190201 which is later than 0001. 
            // Since 0001 is executed first, I cannot add the foreign key constraint here if the table doesn't exist yet.
            // However, the user asked to centralize. I should probably move the foreign key addition to a later migration or keep it here and rename this migration to be later. 
            // BUT, create_users is a core Laravel migration. 
            // I will use `bigInteger` for now and add constraint later? No, that defeats the purpose.
            // Best approach: Keep `create_users` clean, but since `customer_groups` is created later, I should probably leave the FK column here but without `constrained()` if I can't guarantee order, OR (better) I can just define the column here and add the constraint in the `create_customer_groups` table migration (inverse) or keep a single 'add_relations' migration.
            // HOWEVER, looking at the file dates:
            // 0001... create_users
            // 2025... create_customer_groups
            // If I add `customer_group_id` here, `customer_groups` table won't exist.
            // So I will add the column `unsignedBigInteger('customer_group_id')->nullable()` here, but add the constraint in `create_customer_groups_table` or just assume I can't add `constrained()` here.
            
            // Wait, if I am centralizing, maybe I should just keep the column here and allow the constraint to be added later or remove the constraint from here and add it in a separate "relations" migration?
            // Or better: Use the standard Laravel practice. If `customer_groups` is created later, I should add the column and FK in a migration that runs AFTER both tables exist. 
            // But the user wants to remove "add..." migrations.
            // So, I will just add the column here. I will add the foreign key constraint in the `create_customer_groups_table.php` using `Schema::table('users', ...)` inside its `up` method, OR I will just leave the column here and the constraint will be added if I change the timestamp of this migration? No, 0001 is fixed.
            
            // Decision: Add the column here. Add the constraint in `2025_12_19_190201_create_customer_groups_table.php`.
            $table->foreignId('customer_group_id')->nullable(); // Constraint added in create_customer_groups_table
            
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};