<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Tax;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Taxes
        if (Tax::count() === 0) {
            Tax::factory()->create(['name' => 'IVA 19%', 'rate' => 19.00]);
            Tax::factory()->create(['name' => 'IVA 10%', 'rate' => 10.00]);
        }
        $taxes = Tax::all();

        // Brands
        if (Brand::count() === 0) {
            $brandNames = [
                'Apple', 'Samsung', 'Sony', 'Dell', 'Nike', 'Adidas', 'Zara', 'IKEA', 'H&M', 'LG',
                'HP', 'Lenovo', 'Asus', 'Acer', 'Microsoft', 'Logitech', 'Razer', 'Corsair', 'Kingston',
                'Seagate', 'Canon', 'Nikon', 'GoPro', 'DJI', 'Garmin', 'Fitbit', 'Nintendo', 'PlayStation', 'Xbox'
            ];
            foreach ($brandNames as $name) {
                Brand::factory()->create(['name' => $name, 'slug' => Str::slug($name)]);
            }
        }
        $brands = Brand::all();

        // Get all categories
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->info('No categories found. Skipping product seeding.');
            return;
        }

        foreach ($categories as $category) {
            // Generate 10 to 30 products per category
            $productCount = rand(2, 10);
            
            $this->command->info("Creating {$productCount} products for category: {$category->name}");

            for ($i = 0; $i < $productCount; $i++) {
                $name = $faker->unique()->words(rand(2, 4), true);
                $name = Str::title($name);
                
                $basePrice = $faker->randomFloat(2, 10, 5000);
                $hasDiscount = $faker->boolean(30);
                $discountPrice = $hasDiscount ? $basePrice * $faker->randomFloat(2, 0.5, 0.9) : null;

                // Create Product
                $product = Product::create([
                    'brand_id' => $brands->random()->id,
                    'sku' => strtoupper(Str::slug($name)) . '-' . rand(100, 9999) . '-' . Str::random(3),
                    'name' => $name,
                    'slug' => Str::slug($name) . '-' . rand(1000, 9999),
                    'short_description' => $faker->paragraph(1),
                    'description' => $faker->paragraphs(3, true),
                    'guarantee' => $faker->randomElement(['1 year', '2 years', '6 months', 'Lifetime']),
                    'base_price' => $basePrice,
                    'discount_price' => $discountPrice,
                    'tax_id' => $taxes->random()->id,
                    'weight' => $faker->randomFloat(2, 0.1, 10),
                    'width' => $faker->numberBetween(5, 100),
                    'height' => $faker->numberBetween(5, 100),
                    'depth' => $faker->numberBetween(5, 100),
                    'stock_quantity' => $faker->numberBetween(0, 100),
                    'is_active' => true,
                    'is_featured' => $faker->boolean(10),
                ]);

                // Attach Category and all ancestors
                $categoryIds = $this->getCategoryHierarchyIds($category);
                $product->categories()->attach($categoryIds);

                // Create Images
                $this->createProductImages($product, $category->slug);
            }
        }
    }

    private function getCategoryHierarchyIds(Category $category): array
    {
        $ids = [$category->id];
        $currentCategory = $category;
        
        while ($currentCategory->parent_id) {
            $currentCategory = Category::find($currentCategory->parent_id);
            if ($currentCategory) {
                $ids[] = $currentCategory->id;
            } else {
                break;
            }
        }
        
        return array_unique($ids);
    }
    
    private function createProductImages(Product $product, string $categorySlug): void
    {
         // Try to derive a keyword from category slug or use generic ones
         $keywords = explode('-', $categorySlug);
         $keyword = count($keywords) > 0 ? $keywords[0] : 'tech';
         
         // Fallback if keyword is too short or weird
         if (strlen($keyword) < 3) {
             $keyword = 'technology';
         }

         for ($j = 0; $j < rand(1, 3); $j++) {
             $lock = rand(1, 100000);
             ProductImage::create([
                'product_id' => $product->id,
                'image_path' => "https://loremflickr.com/640/480/{$keyword}?lock={$lock}",
                'is_primary' => $j === 0,
                'position' => $j,
             ]);
         }
    }
}
