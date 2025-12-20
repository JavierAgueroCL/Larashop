<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        Language::create(['code' => 'es', 'name' => 'Español', 'is_active' => true, 'is_default' => true]);
        Language::create(['code' => 'en', 'name' => 'English', 'is_active' => true, 'is_default' => false]);
        Language::create(['code' => 'fr', 'name' => 'Français', 'is_active' => true, 'is_default' => false]);
    }
}
