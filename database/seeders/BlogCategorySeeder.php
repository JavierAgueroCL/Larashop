<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BlogCategory::create(['name' => 'Technology', 'slug' => 'technology']);
        BlogCategory::create(['name' => 'Fashion', 'slug' => 'fashion']);
        BlogCategory::create(['name' => 'Lifestyle', 'slug' => 'lifestyle']);
    }
}