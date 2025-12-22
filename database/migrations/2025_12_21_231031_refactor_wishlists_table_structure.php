<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Rename existing table to wishlist_items if not already done
        if (Schema::hasTable('wishlists') && !Schema::hasTable('wishlist_items')) {
            Schema::rename('wishlists', 'wishlist_items');
        }

        // 2. Create the new wishlists (groups) table
        if (!Schema::hasTable('wishlists')) {
            Schema::create('wishlists', function (Blueprint $table) {
                $table->id();
                // Explicitly name the FK to avoid collision with the old table's FK (which might still be 'wishlists_user_id_foreign')
                $table->foreignId('user_id')->constrained('users', 'id', 'wishlists_group_user_id_foreign')->cascadeOnDelete();
                $table->string('name');
                $table->string('slug')->unique(); // For sharing: user-id-slug
                $table->boolean('is_public')->default(false);
                $table->boolean('is_default')->default(false);
                $table->timestamps();
            });
        }

        // 3. Add wishlist_id to wishlist_items
        if (Schema::hasColumn('wishlist_items', 'wishlist_id') === false) {
             Schema::table('wishlist_items', function (Blueprint $table) {
                $table->foreignId('wishlist_id')->nullable()->after('id')->constrained('wishlists')->cascadeOnDelete();
            });
        }

        // 4. Migrate existing data
        // For every user who has items, create a default wishlist and move items there
        // Only run this if we have items with null wishlist_id
        $itemsToMigrate = DB::table('wishlist_items')->whereNull('wishlist_id')->exists();
        
        if ($itemsToMigrate) {
            $usersWithItems = DB::table('wishlist_items')->whereNull('wishlist_id')->select('user_id')->distinct()->get();

            foreach ($usersWithItems as $user) {
                // Check if user already has a default wishlist to avoid dupes if re-running
                $wishlistId = DB::table('wishlists')
                    ->where('user_id', $user->user_id)
                    ->where('is_default', true)
                    ->value('id');

                if (!$wishlistId) {
                    $wishlistId = DB::table('wishlists')->insertGetId([
                        'user_id' => $user->user_id,
                        'name' => 'My Wishlist',
                        'slug' => 'my-wishlist-' . $user->user_id . '-' . Str::random(6),
                        'is_public' => false,
                        'is_default' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::table('wishlist_items')
                    ->where('user_id', $user->user_id)
                    ->whereNull('wishlist_id')
                    ->update(['wishlist_id' => $wishlistId]);
            }
        }

        // 5. Cleanup wishlist_items
        if (Schema::hasColumn('wishlist_items', 'user_id')) {
            Schema::table('wishlist_items', function (Blueprint $table) {
                // Make wishlist_id required now
                $table->unsignedBigInteger('wishlist_id')->nullable(false)->change();
                
                // Remove user_id as it's now on the parent wishlist
                // We need to drop the foreign key first. 
                // The name is likely 'wishlists_user_id_foreign' (from original table) OR 'wishlist_items_user_id_foreign' (if auto-renamed).
                // Usually it stays as original.
                $table->dropForeign('wishlists_user_id_foreign');
                
                // Now we can safe drop the unique index that might be used by the FK
                $table->dropUnique('wishlists_user_id_product_id_unique');

                $table->dropColumn('user_id');

                // Add new unique constraint
                $table->unique(['wishlist_id', 'product_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a complex reverse, simplifying for now:
        // We would lose the "Multiple lists" structure and flatten back to user_id -> product_id
        
        Schema::table('wishlist_items', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
        });

        // Re-assign user_ids based on wishlist ownership
        // (Simplified logic, assuming strictly for rollback)
        $items = DB::table('wishlist_items')
            ->join('wishlists', 'wishlist_items.wishlist_id', '=', 'wishlists.id')
            ->select('wishlist_items.id', 'wishlists.user_id')
            ->get();

        foreach ($items as $item) {
            DB::table('wishlist_items')->where('id', $item->id)->update(['user_id' => $item->user_id]);
        }

        Schema::table('wishlist_items', function (Blueprint $table) {
            $table->dropForeign(['wishlist_id']);
            $table->dropColumn('wishlist_id');
            $table->unique(['user_id', 'product_id']);
        });

        Schema::dropIfExists('wishlists');
        Schema::rename('wishlist_items', 'wishlists');
    }
};