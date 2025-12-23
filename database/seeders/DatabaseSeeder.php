<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            SettingSeeder::class,
            ProductSeeder::class,
            FeaturedProductSeeder::class,
            CarrierSeeder::class,
            PageSeeder::class,
            // LanguageSeeder::class,
            // CurrencySeeder::class,
            MenuSeeder::class,
            RegionesSeeder::class,
            ProvinciasSeeder::class,
            ComunasSeeder::class,
            AddressSeeder::class,
            SliderSeeder::class,
            BannerSeeder::class,
            BlogCategorySeeder::class,
            BlogPostSeeder::class,
        ]);

        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
