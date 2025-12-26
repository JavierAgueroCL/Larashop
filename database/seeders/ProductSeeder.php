<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Tax;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Taxes
        if (Tax::count() === 0) {
        $tax = Tax::factory()->create(['name' => 'IVA 19%', 'rate' => 19.00]);
            Tax::factory()->create(['name' => 'IVA 10%', 'rate' => 10.00]);
        }
        $taxes = Tax::all();

        // Brands
        if (Brand::count() === 0) {
            $brandNames = ['Apple', 'Samsung', 'Sony', 'Dell', 'Nike', 'Adidas', 'Zara', 'IKEA', 'H&M', 'LG'];
            foreach ($brandNames as $name) {
                Brand::factory()->create(['name' => $name, 'slug' => Str::slug($name)]);
            }
        }
        $brands = Brand::all();

        // Realistic Product Data Map
        $productsMap = [
            'ordenadores' => [
                ['name' => 'MacBook Pro 16 M3', 'price' => rand(5000, 30000), 'img' => 'laptop,macbook'],
                ['name' => 'Dell XPS 15', 'price' => rand(5000, 30000), 'img' => 'laptop,dell'],
                ['name' => 'Lenovo ThinkPad X1', 'price' => rand(5000, 30000), 'img' => 'laptop,work'],
                ['name' => 'Asus ROG Zephyrus', 'price' => rand(5000, 30000), 'img' => 'gaming,laptop'],
            ],
            'moviles' => [
                ['name' => 'iPhone 15 Pro Max', 'price' => rand(5000, 30000), 'img' => 'iphone,smartphone'],
                ['name' => 'Samsung Galaxy S24 Ultra', 'price' => rand(5000, 30000), 'img' => 'samsung,phone'],
                ['name' => 'Google Pixel 8 Pro', 'price' => rand(5000, 30000), 'img' => 'pixel,phone'],
                ['name' => 'OnePlus 12', 'price' => rand(5000, 30000), 'img' => 'oneplus,phone'],
            ],
            'tablets' => [
                ['name' => 'iPad Pro 12.9"', 'price' => rand(5000, 30000), 'img' => 'ipad,tablet'],
                ['name' => 'Samsung Galaxy Tab S9', 'price' => rand(5000, 30000), 'img' => 'tablet,android'],
            ],
            'hombre' => [
                ['name' => 'Chaqueta de Cuero Clásica', 'price' => rand(5000, 30000), 'img' => 'jacket,leather'],
                ['name' => 'Jeans Slim Fit Azul', 'price' => rand(5000, 30000), 'img' => 'jeans,men'],
                ['name' => 'Camiseta Básica Blanca', 'price' => rand(5000, 30000), 'img' => 'tshirt,men'],
                ['name' => 'Zapatillas Running Pro', 'price' => rand(5000, 30000), 'img' => 'sneakers,running'],
            ],
            'mujer' => [
                ['name' => 'Vestido Floral Verano', 'price' => rand(5000, 30000), 'img' => 'dress,summer'],
                ['name' => 'Blusa de Seda', 'price' => rand(5000, 30000), 'img' => 'blouse,woman'],
                ['name' => 'Bolso de Mano Elegante', 'price' => rand(5000, 30000), 'img' => 'handbag,fashion'],
                ['name' => 'Botines de Piel', 'price' => rand(5000, 30000), 'img' => 'boots,fashion'],
            ],
            'muebles' => [
                ['name' => 'Sofá 3 Plazas Nórdico', 'price' => rand(5000, 30000), 'img' => 'sofa,livingroom'],
                ['name' => 'Mesa de Centro Madera', 'price' => rand(5000, 30000), 'img' => 'table,coffee'],
                ['name' => 'Silla Eames Blanca', 'price' => rand(5000, 30000), 'img' => 'chair,modern'],
            ],
            'decoracion' => [
                ['name' => 'Lámpara de Pie Arco', 'price' => rand(5000, 30000), 'img' => 'lamp,floor'],
                ['name' => 'Espejo Redondo Dorado', 'price' => rand(5000, 30000), 'img' => 'mirror,wall'],
                ['name' => 'Juego de Cojines Suaves', 'price' => rand(5000, 30000), 'img' => 'pillows,decor'],
            ],
        ];

        foreach ($productsMap as $categorySlug => $products) {
            $category = Category::where('slug', $categorySlug)->first();
            
            if (!$category) continue;

            foreach ($products as $index => $item) {
                // Create Product
                $product = Product::create([
                    'brand_id' => $brands->random()->id,
                    'sku' => strtoupper(Str::slug($item['name'])) . '-' . rand(100, 999),
                    'name' => $item['name'],
                    'slug' => Str::slug($item['name']) . '-' . rand(1000, 9999),
                    'short_description' => 'This is a premium product designed for excellence. ' . $item['name'] . ' offers the best value.',
                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
                    'guarantee' => 'We offer a 2-year warranty on this product. If you encounter any issues, please contact our support team for assistance.',
                    'base_price' => $item['price'],
                    'discount_price' => rand(0, 100) < 30 ? $item['price'] * 0.8 : null,
                    'tax_id' => $taxes->random()->id,
                    'weight' => rand(10, 500) / 100, // 0.10 to 5.00 kg
                    'width' => rand(10, 20), // cm
                    'height' => rand(10, 20), // cm
                    'depth' => rand(10, 20), // cm
                    'stock_quantity' => rand(0, 100) < 10 ? 0 : rand(10, 100),
                    'is_active' => true,
                    'is_featured' => rand(0, 1) === 1,
                ]);

                // Attach Category (and parent if exists)
                $product->categories()->attach($category->id);
                if ($category->parent_id) {
                    $product->categories()->attach($category->parent_id);
                }

                // Create Images using LoremFlickr
                // Use lock to keep image stable per product seed
                $lock = rand(1, 50000);
                $keywords = $item['img'];
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => "https://loremflickr.com/600/600/{$keywords}?lock={$lock}",
                    'is_primary' => true,
                    'position' => 0,
                ]);

                // Secondary image
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => "https://loremflickr.com/600/600/{$keywords}?lock=" . ($lock + 1),
                    'is_primary' => false,
                    'position' => 1,
                ]);
            }
        }
    }
}
