# Guía de Inicio Rápido - LaraShop

Esta guía te ayudará a comenzar el desarrollo de LaraShop siguiendo las mejores prácticas y el plan establecido.

---

## PRERREQUISITOS

Antes de comenzar, asegúrate de tener:

- PHP 8.2 o superior
- Composer
- Node.js y NPM
- Docker y Docker Compose (ya configurado en el proyecto)
- Git

---

## PASO 1: CONFIGURACIÓN INICIAL

### 1.1 Variables de Entorno

Verifica tu archivo `.env` y ajusta las siguientes configuraciones:

```env
APP_NAME=LaraShop
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=larashop
DB_USERNAME=root
DB_PASSWORD=password

MAIL_MAILER=log
MAIL_FROM_ADDRESS=info@larashop.com
MAIL_FROM_NAME="${APP_NAME}"

# Configuración de la tienda
SHOP_NAME=LaraShop
SHOP_EMAIL=info@larashop.com
SHOP_CURRENCY=EUR
SHOP_LOCALE=es
SHOP_TAX_INCLUDED=true
SHOP_GUEST_CHECKOUT=true
```

### 1.2 Instalar Dependencias

```bash
composer install
npm install
```

---

## PASO 2: COMENZAR CON FASE 1 - FUNDAMENTOS

### 2.1 Instalar Laravel Breeze

Laravel Breeze nos proporcionará autenticación básica que luego personalizaremos.

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
```

Cuando te pregunte, selecciona:
- Stack: Blade
- Dark mode: yes
- Testing: Pest

```bash
npm install
npm run build
```

### 2.2 Personalizar Autenticación

Necesitaremos agregar campos adicionales a la tabla de usuarios:

**Crear migración:**
```bash
php artisan make:migration add_additional_fields_to_users_table
```

**Editar la migración creada:**
```php
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
```

### 2.3 Crear Estructura de Directorios

Crea las carpetas que usaremos a lo largo del proyecto:

```bash
mkdir -p app/Actions/Cart
mkdir -p app/Actions/Order
mkdir -p app/Actions/Product
mkdir -p app/DataTransferObjects
mkdir -p app/Events
mkdir -p app/Listeners
mkdir -p app/Policies
mkdir -p app/Repositories/Contracts
mkdir -p app/Repositories/Eloquent
mkdir -p app/Services/Cart
mkdir -p app/Services/Checkout
mkdir -p app/Services/Order
mkdir -p app/Services/Payment
mkdir -p app/Services/Pricing
mkdir -p app/Services/Product
mkdir -p app/Services/Shipping
mkdir -p app/Services/Translation
```

### 2.4 Configurar Tailwind

**Editar `tailwind.config.js`:**

```js
import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.blade.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0ea5e9',
                    600: '#0284c7',
                    700: '#0369a1',
                    800: '#075985',
                    900: '#0c4a6e',
                },
                secondary: {
                    500: '#6366f1',
                    600: '#4f46e5',
                    700: '#4338ca',
                }
            },
        },
    },

    plugins: [forms],
};
```

### 2.5 Configurar Archivos de Configuración Personalizados

**Crear `config/shop.php`:**

```bash
touch config/shop.php
```

```php
<?php

return [
    'name' => env('SHOP_NAME', 'LaraShop'),
    'email' => env('SHOP_EMAIL', 'info@larashop.com'),
    'currency' => env('SHOP_CURRENCY', 'EUR'),
    'locale' => env('SHOP_LOCALE', 'es'),
    'tax_included' => env('SHOP_TAX_INCLUDED', true),
    'guest_checkout' => env('SHOP_GUEST_CHECKOUT', true),
    'low_stock_threshold' => env('SHOP_LOW_STOCK_THRESHOLD', 5),

    'pagination' => [
        'products_per_page' => 12,
        'orders_per_page' => 10,
    ],

    'images' => [
        'product' => [
            'max_size' => 2048, // KB
            'dimensions' => [
                'large' => [800, 800],
                'medium' => [400, 400],
                'thumb' => [150, 150],
            ],
        ],
    ],
];
```

**Crear `config/payment.php`:**

```bash
touch config/payment.php
```

```php
<?php

return [
    'default' => env('PAYMENT_GATEWAY', 'bank_transfer'),

    'gateways' => [
        'bank_transfer' => [
            'enabled' => true,
            'name' => 'Transferencia Bancaria',
            'description' => 'Realiza una transferencia bancaria a nuestra cuenta.',
        ],
        'paypal' => [
            'enabled' => env('PAYPAL_ENABLED', false),
            'name' => 'PayPal',
            'mode' => env('PAYPAL_MODE', 'sandbox'),
            'client_id' => env('PAYPAL_CLIENT_ID'),
            'secret' => env('PAYPAL_SECRET'),
        ],
    ],
];
```

**Crear `config/shipping.php`:**

```bash
touch config/shipping.php
```

```php
<?php

return [
    'default_weight_unit' => 'g', // gramos
    'default_dimension_unit' => 'cm',

    'free_shipping_threshold' => env('FREE_SHIPPING_THRESHOLD', 50.00),
];
```

---

## PASO 3: FASE 2 - CATÁLOGO DE PRODUCTOS

### 3.1 Crear Migraciones Básicas

Crea las migraciones en orden de dependencias:

```bash
# Customer Groups (necesaria para users)
php artisan make:migration create_customer_groups_table

# Brands
php artisan make:migration create_brands_table

# Categories
php artisan make:migration create_categories_table

# Taxes
php artisan make:migration create_taxes_table

# Products
php artisan make:migration create_products_table

# Product Images
php artisan make:migration create_product_images_table

# Product Categories (pivot)
php artisan make:migration create_product_categories_table

# Attributes
php artisan make:migration create_attributes_table
php artisan make:migration create_attribute_values_table

# Product Combinations
php artisan make:migration create_product_combinations_table
php artisan make:migration create_product_combination_values_table
```

### 3.2 Ejemplo de Migración: Brands

**database/migrations/xxxx_create_brands_table.php:**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 150)->unique();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('slug');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
```

### 3.3 Crear Modelos

```bash
php artisan make:model Brand
php artisan make:model Category
php artisan make:model Product
php artisan make:model ProductImage
php artisan make:model Attribute
php artisan make:model AttributeValue
php artisan make:model ProductCombination
```

### 3.4 Ejemplo de Modelo: Product

**app/Models/Product.php:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'brand_id',
        'sku',
        'name',
        'slug',
        'short_description',
        'description',
        'is_digital',
        'is_active',
        'is_featured',
        'base_price',
        'cost_price',
        'tax_id',
        'weight',
        'width',
        'height',
        'depth',
        'stock_quantity',
        'low_stock_threshold',
        'has_combinations',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_digital' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'has_combinations' => 'boolean',
        'base_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'depth' => 'decimal:2',
    ];

    // Relaciones
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories')
                    ->withPivot('is_primary');
    }

    public function combinations(): HasMany
    {
        return $this->hasMany(ProductCombination::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    // Accessors
    public function getPrimaryImageAttribute()
    {
        return $this->images()->where('is_primary', true)->first()?->image_path
            ?? $this->images()->first()?->image_path
            ?? '/images/placeholder.jpg';
    }

    public function getBasePriceFormattedAttribute()
    {
        return number_format($this->base_price, 2, ',', '.') . ' €';
    }

    public function getIsLowStockAttribute()
    {
        return $this->stock_quantity <= $this->low_stock_threshold && $this->stock_quantity > 0;
    }

    public function getIsOutOfStockAttribute()
    {
        return $this->stock_quantity <= 0;
    }
}
```

### 3.5 Crear Repositorio

**app/Repositories/Contracts/ProductRepositoryInterface.php:**

```php
<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function find(int $id): ?Product;
    public function findBySlug(string $slug): ?Product;
    public function all(): Collection;
    public function paginate(int $perPage = 12): LengthAwarePaginator;
    public function create(array $data): Product;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getFeatured(int $limit = 10): Collection;
    public function getNew(int $limit = 10): Collection;
    public function getBestSellers(int $limit = 10): Collection;
    public function search(string $query): Collection;
    public function filterByCategory(int $categoryId): Collection;
}
```

**app/Repositories/Eloquent/ProductRepository.php:**

```php
<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(protected Product $model)
    {
    }

    public function find(int $id): ?Product
    {
        return $this->model->with(['brand', 'images', 'categories'])->find($id);
    }

    public function findBySlug(string $slug): ?Product
    {
        return $this->model->with(['brand', 'images', 'categories', 'combinations'])
                           ->where('slug', $slug)
                           ->first();
    }

    public function all(): Collection
    {
        return $this->model->with(['brand', 'images'])->get();
    }

    public function paginate(int $perPage = 12): LengthAwarePaginator
    {
        return $this->model->with(['brand', 'images'])
                           ->active()
                           ->paginate($perPage);
    }

    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $product = $this->find($id);
        return $product ? $product->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $product = $this->find($id);
        return $product ? $product->delete() : false;
    }

    public function getFeatured(int $limit = 10): Collection
    {
        return $this->model->with(['brand', 'images'])
                           ->active()
                           ->featured()
                           ->limit($limit)
                           ->get();
    }

    public function getNew(int $limit = 10): Collection
    {
        return $this->model->with(['brand', 'images'])
                           ->active()
                           ->latest()
                           ->limit($limit)
                           ->get();
    }

    public function getBestSellers(int $limit = 10): Collection
    {
        return $this->model->with(['brand', 'images'])
                           ->active()
                           ->orderBy('sales_count', 'desc')
                           ->limit($limit)
                           ->get();
    }

    public function search(string $query): Collection
    {
        return $this->model->with(['brand', 'images'])
                           ->active()
                           ->whereFullText(['name', 'short_description', 'description'], $query)
                           ->get();
    }

    public function filterByCategory(int $categoryId): Collection
    {
        return $this->model->with(['brand', 'images'])
                           ->active()
                           ->whereHas('categories', function ($q) use ($categoryId) {
                               $q->where('categories.id', $categoryId);
                           })
                           ->get();
    }
}
```

### 3.6 Registrar Repositorio en Service Provider

**app/Providers/AppServiceProvider.php:**

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Eloquent\ProductRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Registrar repositorios
        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
```

---

## PASO 4: CREAR SEEDERS

### 4.1 Seeder de Categorías

```bash
php artisan make:seeder CategorySeeder
```

**database/seeders/CategorySeeder.php:**

```php
<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electrónica',
                'slug' => 'electronica',
                'children' => [
                    ['name' => 'Ordenadores', 'slug' => 'ordenadores'],
                    ['name' => 'Móviles', 'slug' => 'moviles'],
                    ['name' => 'Tablets', 'slug' => 'tablets'],
                ]
            ],
            [
                'name' => 'Ropa',
                'slug' => 'ropa',
                'children' => [
                    ['name' => 'Hombre', 'slug' => 'hombre'],
                    ['name' => 'Mujer', 'slug' => 'mujer'],
                    ['name' => 'Niños', 'slug' => 'ninos'],
                ]
            ],
            [
                'name' => 'Hogar',
                'slug' => 'hogar',
                'children' => [
                    ['name' => 'Muebles', 'slug' => 'muebles'],
                    ['name' => 'Decoración', 'slug' => 'decoracion'],
                ]
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create([
                'name' => $categoryData['name'],
                'slug' => $categoryData['slug'],
                'is_active' => true,
                'position' => 0,
            ]);

            if (isset($categoryData['children'])) {
                foreach ($categoryData['children'] as $index => $childData) {
                    Category::create([
                        'parent_id' => $category->id,
                        'name' => $childData['name'],
                        'slug' => $childData['slug'],
                        'is_active' => true,
                        'position' => $index,
                    ]);
                }
            }
        }
    }
}
```

### 4.2 Seeder de Settings

```bash
php artisan make:seeder SettingSeeder
```

**database/seeders/SettingSeeder.php:**

```php
<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'shop_name', 'value' => 'LaraShop', 'type' => 'string', 'group' => 'general'],
            ['key' => 'shop_email', 'value' => 'info@larashop.com', 'type' => 'string', 'group' => 'general'],
            ['key' => 'shop_phone', 'value' => '+34 900 000 000', 'type' => 'string', 'group' => 'general'],

            // Shop
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'shop'],
            ['key' => 'allow_guest_checkout', 'value' => '1', 'type' => 'boolean', 'group' => 'shop'],
            ['key' => 'products_per_page', 'value' => '12', 'type' => 'integer', 'group' => 'shop'],

            // Currencies
            ['key' => 'default_currency', 'value' => 'EUR', 'type' => 'string', 'group' => 'currency'],

            // Taxes
            ['key' => 'prices_include_tax', 'value' => '1', 'type' => 'boolean', 'group' => 'tax'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
```

---

## PASO 5: EJECUTAR MIGRACIONES Y SEEDERS

```bash
php artisan migrate:fresh
php artisan db:seed
```

---

## PASO 6: CREAR FACTORIES PARA TESTING

```bash
php artisan make:factory ProductFactory
php artisan make:factory BrandFactory
php artisan make:factory CategoryFactory
```

**database/factories/ProductFactory.php:**

```php
<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Tax;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'brand_id' => Brand::factory(),
            'sku' => strtoupper(Str::random(10)),
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraphs(3, true),
            'is_digital' => false,
            'is_active' => true,
            'is_featured' => fake()->boolean(20), // 20% de probabilidad
            'base_price' => fake()->randomFloat(2, 10, 500),
            'cost_price' => fake()->randomFloat(2, 5, 250),
            'tax_id' => Tax::factory(),
            'weight' => fake()->numberBetween(100, 5000),
            'stock_quantity' => fake()->numberBetween(0, 100),
            'low_stock_threshold' => 5,
            'has_combinations' => false,
        ];
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock_quantity' => 0,
        ]);
    }
}
```

---

## PASO 7: ESCRIBIR PRIMER TEST

```bash
php artisan make:test ProductTest
```

**tests/Feature/ProductTest.php:**

```php
<?php

use App\Models\Product;

test('can list active products', function () {
    Product::factory()->count(5)->create(['is_active' => true]);
    Product::factory()->count(2)->create(['is_active' => false]);

    $response = $this->get(route('products.index'));

    $response->assertOk();
    $response->assertViewHas('products', function ($products) {
        return $products->count() === 5;
    });
});

test('can view single product', function () {
    $product = Product::factory()->create();

    $response = $this->get(route('products.show', $product->slug));

    $response->assertOk();
    $response->assertSee($product->name);
});
```

---

## RESUMEN DE COMANDOS ÚTILES

```bash
# Desarrollo
composer dev              # Levantar servidor + queue + logs + vite
npm run dev              # Solo Vite

# Testing
composer test            # Ejecutar tests
php artisan test --filter ProductTest

# Base de datos
php artisan migrate      # Ejecutar migraciones
php artisan migrate:fresh --seed   # Reset + seed
php artisan db:seed --class=CategorySeeder

# Cache
php artisan optimize:clear    # Limpiar todo el cache
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Generar código
php artisan make:model NombreModelo -mf    # Modelo + migración + factory
php artisan make:controller NombreController --resource
php artisan make:request NombreRequest
```

---

## PRÓXIMOS PASOS

Una vez completada la Fase 1 y 2:

1. Continuar con Fase 3: Carrito de compras
2. Implementar el sistema de precios e impuestos
3. Desarrollar el flujo de checkout
4. Integrar métodos de pago
5. Configurar envíos

Consulta el `PLAN_MAESTRO.md` para detalles completos de cada fase.

---

**Última actualización:** 19 de diciembre de 2025
