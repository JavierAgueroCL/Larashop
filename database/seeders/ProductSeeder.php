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
            Tax::factory()->create(['name' => 'IVA 21%', 'rate' => 21.00]);
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
                ['name' => 'MacBook Pro 16 M3', 'price' => 2499.00, 'img' => 'laptop,macbook'],
                ['name' => 'Dell XPS 15', 'price' => 1899.00, 'img' => 'laptop,dell'],
                ['name' => 'Lenovo ThinkPad X1', 'price' => 1599.00, 'img' => 'laptop,work'],
                ['name' => 'Asus ROG Zephyrus', 'price' => 2100.00, 'img' => 'gaming,laptop'],
            ],
            'moviles' => [
                ['name' => 'iPhone 15 Pro Max', 'price' => 1200.00, 'img' => 'iphone,smartphone'],
                ['name' => 'Samsung Galaxy S24 Ultra', 'price' => 1150.00, 'img' => 'samsung,phone'],
                ['name' => 'Google Pixel 8 Pro', 'price' => 999.00, 'img' => 'pixel,phone'],
                ['name' => 'OnePlus 12', 'price' => 800.00, 'img' => 'oneplus,phone'],
            ],
            'tablets' => [
                ['name' => 'iPad Pro 12.9"', 'price' => 1099.00, 'img' => 'ipad,tablet'],
                ['name' => 'Samsung Galaxy Tab S9', 'price' => 899.00, 'img' => 'tablet,android'],
            ],
            'hombre' => [
                ['name' => 'Chaqueta de Cuero Clásica', 'price' => 150.00, 'img' => 'jacket,leather'],
                ['name' => 'Jeans Slim Fit Azul', 'price' => 45.00, 'img' => 'jeans,men'],
                ['name' => 'Camiseta Básica Blanca', 'price' => 15.00, 'img' => 'tshirt,men'],
                ['name' => 'Zapatillas Running Pro', 'price' => 85.00, 'img' => 'sneakers,running'],
            ],
            'mujer' => [
                ['name' => 'Vestido Floral Verano', 'price' => 65.00, 'img' => 'dress,summer'],
                ['name' => 'Blusa de Seda', 'price' => 55.00, 'img' => 'blouse,woman'],
                ['name' => 'Bolso de Mano Elegante', 'price' => 120.00, 'img' => 'handbag,fashion'],
                ['name' => 'Botines de Piel', 'price' => 95.00, 'img' => 'boots,fashion'],
            ],
            'muebles' => [
                ['name' => 'Sofá 3 Plazas Nórdico', 'price' => 699.00, 'img' => 'sofa,livingroom'],
                ['name' => 'Mesa de Centro Madera', 'price' => 120.00, 'img' => 'table,coffee'],
                ['name' => 'Silla Eames Blanca', 'price' => 45.00, 'img' => 'chair,modern'],
            ],
            'decoracion' => [
                ['name' => 'Lámpara de Pie Arco', 'price' => 89.00, 'img' => 'lamp,floor'],
                ['name' => 'Espejo Redondo Dorado', 'price' => 60.00, 'img' => 'mirror,wall'],
                ['name' => 'Juego de Cojines Suaves', 'price' => 25.00, 'img' => 'pillows,decor'],
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
                    'weight' => rand(100, 5000) / 100, // 1.00 to 50.00 kg
                    'width' => rand(10, 100), // cm
                    'height' => rand(10, 100), // cm
                    'depth' => rand(10, 100), // cm
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
