# Plan Maestro - LaraShop
## Tienda Online Laravel (PrestaShop 9 Equivalent)

**Fecha de inicio:** 19 de diciembre de 2025
**Laravel Version:** 12.x
**Estado:** Planificación

---

## TABLA DE CONTENIDOS

1. [Visión General](#visión-general)
2. [Arquitectura del Sistema](#arquitectura-del-sistema)
3. [Base de Datos](#base-de-datos)
4. [Estructura de Directorios](#estructura-de-directorios)
5. [Plan de Implementación por Fases](#plan-de-implementación-por-fases)
6. [Servicios y Repositorios](#servicios-y-repositorios)
7. [Frontend (Blade + Tailwind)](#frontend-blade--tailwind)
8. [Sistema de Módulos](#sistema-de-módulos)
9. [Testing](#testing)
10. [Seguridad](#seguridad)

---

## VISIÓN GENERAL

LaraShop es una plataforma e-commerce completa desarrollada en Laravel 12, que replica todas las funcionalidades principales de PrestaShop 9 con una arquitectura moderna, escalable y mantenible.

### Principios de Diseño

- **Arquitectura Limpia:** Separación de responsabilidades (Service/Repository pattern)
- **SOLID:** Cumplimiento de principios SOLID
- **DRY:** Don't Repeat Yourself
- **Extensibilidad:** Sistema de módulos/plugins sin modificar el core
- **Mobile-First:** Diseño responsivo priorizando dispositivos móviles
- **Performance:** Cache, queues, optimización de consultas
- **Seguridad:** Validación, sanitización, políticas de acceso

---

## ARQUITECTURA DEL SISTEMA

### Capas de la Aplicación

```
┌─────────────────────────────────────────┐
│         PRESENTATION LAYER              │
│  (Controllers, Blade Views, API)        │
└─────────────────────────────────────────┘
                  ↓
┌─────────────────────────────────────────┐
│         APPLICATION LAYER               │
│  (Services, Actions, DTOs)              │
└─────────────────────────────────────────┘
                  ↓
┌─────────────────────────────────────────┐
│         DOMAIN LAYER                    │
│  (Models, Business Logic, Events)       │
└─────────────────────────────────────────┘
                  ↓
┌─────────────────────────────────────────┐
│         INFRASTRUCTURE LAYER            │
│  (Repositories, External Services)      │
└─────────────────────────────────────────┘
```

### Patrones Implementados

- **Service Layer:** Lógica de negocio desacoplada de controladores
- **Repository Pattern:** Abstracción del acceso a datos
- **Observer Pattern:** Eventos y listeners para extensibilidad
- **Strategy Pattern:** Métodos de pago y envío intercambiables
- **Factory Pattern:** Creación de objetos complejos (órdenes, carritos)
- **Decorator Pattern:** Cálculo de precios con impuestos y descuentos

---

## BASE DE DATOS

### Diagrama Entidad-Relación Simplificado

```
USERS ─┬─ ADDRESSES
       ├─ ORDERS ── ORDER_ITEMS ── PRODUCTS
       ├─ CART_ITEMS ── PRODUCTS
       └─ CUSTOMER_GROUPS

PRODUCTS ─┬─ PRODUCT_COMBINATIONS
          ├─ PRODUCT_IMAGES
          ├─ PRODUCT_CATEGORIES
          ├─ PRODUCT_ATTRIBUTES
          ├─ STOCK_MOVEMENTS
          └─ BRANDS

CATEGORIES ── (self-reference para jerarquía)

ORDERS ─┬─ ORDER_ITEMS
        ├─ ORDER_STATUS_HISTORY
        ├─ INVOICES
        └─ SHIPMENTS
```

### Tablas Principales (Migraciones)

#### 1. USUARIOS Y AUTENTICACIÓN

**users**
```sql
- id (bigint, PK)
- email (string, unique)
- password (string)
- first_name (string)
- last_name (string)
- phone (string, nullable)
- is_guest (boolean, default false)
- customer_group_id (FK, nullable)
- last_login_at (timestamp, nullable)
- created_at, updated_at
- deleted_at (soft deletes)
```

**customer_groups**
```sql
- id (bigint, PK)
- name (string)
- discount_percentage (decimal(5,2), default 0)
- created_at, updated_at
```

**addresses**
```sql
- id (bigint, PK)
- user_id (FK)
- address_type (enum: shipping, billing, both)
- first_name (string)
- last_name (string)
- company (string, nullable)
- address_line_1 (string)
- address_line_2 (string, nullable)
- city (string)
- state_province (string)
- postal_code (string)
- country_code (string, 2 chars)
- phone (string)
- is_default (boolean, default false)
- created_at, updated_at
```

#### 2. CATÁLOGO DE PRODUCTOS

**brands**
```sql
- id (bigint, PK)
- name (string)
- slug (string, unique)
- logo (string, nullable)
- description (text, nullable)
- is_active (boolean, default true)
- created_at, updated_at
```

**categories**
```sql
- id (bigint, PK)
- parent_id (FK self, nullable)
- name (string)
- slug (string, unique)
- description (text, nullable)
- image (string, nullable)
- position (integer, default 0)
- is_active (boolean, default true)
- meta_title (string, nullable)
- meta_description (string, nullable)
- created_at, updated_at
```

**products**
```sql
- id (bigint, PK)
- brand_id (FK, nullable)
- sku (string, unique)
- name (string)
- slug (string, unique)
- short_description (text, nullable)
- description (text, nullable)
- is_digital (boolean, default false)
- is_active (boolean, default true)
- is_featured (boolean, default false)
- base_price (decimal(10,2))
- cost_price (decimal(10,2), nullable)
- tax_id (FK, nullable)
- weight (decimal(8,2), nullable) -- en gramos
- width (decimal(8,2), nullable) -- en cm
- height (decimal(8,2), nullable)
- depth (decimal(8,2), nullable)
- stock_quantity (integer, default 0)
- low_stock_threshold (integer, default 5)
- has_combinations (boolean, default false)
- meta_title (string, nullable)
- meta_description (string, nullable)
- views_count (integer, default 0)
- sales_count (integer, default 0)
- created_at, updated_at
- deleted_at (soft deletes)
```

**product_images**
```sql
- id (bigint, PK)
- product_id (FK)
- image_path (string)
- is_primary (boolean, default false)
- position (integer, default 0)
- alt_text (string, nullable)
- created_at, updated_at
```

**product_categories**
```sql
- product_id (FK)
- category_id (FK)
- is_primary (boolean, default false)
- PRIMARY KEY (product_id, category_id)
```

#### 3. ATRIBUTOS Y COMBINACIONES

**attributes**
```sql
- id (bigint, PK)
- name (string) -- Ej: "Color", "Talla"
- slug (string, unique)
- position (integer, default 0)
- created_at, updated_at
```

**attribute_values**
```sql
- id (bigint, PK)
- attribute_id (FK)
- value (string) -- Ej: "Rojo", "XL"
- slug (string)
- color_hex (string, nullable) -- Para colores
- position (integer, default 0)
- created_at, updated_at
```

**product_combinations**
```sql
- id (bigint, PK)
- product_id (FK)
- sku (string, unique)
- price_impact (decimal(10,2), default 0) -- Diferencia con precio base
- weight_impact (decimal(8,2), default 0)
- stock_quantity (integer, default 0)
- is_default (boolean, default false)
- created_at, updated_at
```

**product_combination_values**
```sql
- combination_id (FK)
- attribute_value_id (FK)
- PRIMARY KEY (combination_id, attribute_value_id)
```

#### 4. STOCK

**stock_movements**
```sql
- id (bigint, PK)
- product_id (FK, nullable)
- product_combination_id (FK, nullable)
- movement_type (enum: in, out, adjustment, reserved, released)
- quantity (integer)
- reason (string, nullable)
- reference_type (string, nullable) -- Order, Return, etc.
- reference_id (bigint, nullable)
- user_id (FK, nullable) -- Quien hizo el movimiento
- created_at
```

#### 5. PRECIOS E IMPUESTOS

**taxes**
```sql
- id (bigint, PK)
- name (string) -- Ej: "IVA 21%"
- rate (decimal(5,2)) -- 21.00
- country_code (string, nullable)
- state_province (string, nullable)
- is_active (boolean, default true)
- created_at, updated_at
```

**price_rules**
```sql
- id (bigint, PK)
- name (string)
- rule_type (enum: customer_group, quantity, date_range)
- customer_group_id (FK, nullable)
- product_id (FK, nullable)
- category_id (FK, nullable)
- min_quantity (integer, nullable)
- discount_type (enum: percentage, fixed)
- discount_value (decimal(10,2))
- start_date (date, nullable)
- end_date (date, nullable)
- priority (integer, default 0)
- is_active (boolean, default true)
- created_at, updated_at
```

**coupons**
```sql
- id (bigint, PK)
- code (string, unique)
- description (text, nullable)
- discount_type (enum: percentage, fixed, free_shipping)
- discount_value (decimal(10,2))
- min_purchase_amount (decimal(10,2), nullable)
- max_uses (integer, nullable)
- uses_count (integer, default 0)
- max_uses_per_user (integer, nullable)
- start_date (datetime)
- end_date (datetime)
- is_active (boolean, default true)
- created_at, updated_at
```

**coupon_usage**
```sql
- id (bigint, PK)
- coupon_id (FK)
- user_id (FK)
- order_id (FK)
- used_at (timestamp)
```

#### 6. CARRITO

**carts**
```sql
- id (bigint, PK)
- user_id (FK, nullable) -- null para invitados
- session_id (string, nullable) -- Para invitados
- coupon_id (FK, nullable)
- created_at, updated_at
```

**cart_items**
```sql
- id (bigint, PK)
- cart_id (FK)
- product_id (FK)
- product_combination_id (FK, nullable)
- quantity (integer)
- price_snapshot (decimal(10,2)) -- Precio al momento de agregar
- created_at, updated_at
```

#### 7. PEDIDOS

**orders**
```sql
- id (bigint, PK)
- order_number (string, unique)
- user_id (FK, nullable)
- customer_email (string)
- customer_first_name (string)
- customer_last_name (string)
- customer_phone (string, nullable)
-
- billing_address_id (FK addresses)
- shipping_address_id (FK addresses)
-
- subtotal (decimal(10,2))
- tax_total (decimal(10,2))
- shipping_cost (decimal(10,2))
- discount_total (decimal(10,2))
- grand_total (decimal(10,2))
-
- coupon_id (FK, nullable)
- coupon_discount (decimal(10,2), default 0)
-
- payment_method (string)
- payment_status (enum: pending, processing, completed, failed, refunded)
- payment_transaction_id (string, nullable)
-
- shipping_method (string)
- tracking_number (string, nullable)
-
- current_status (enum: pending, processing, shipped, delivered, cancelled, refunded)
- notes (text, nullable)
-
- created_at, updated_at
```

**order_items**
```sql
- id (bigint, PK)
- order_id (FK)
- product_id (FK)
- product_combination_id (FK, nullable)
- product_name (string) -- Snapshot
- product_sku (string) -- Snapshot
- quantity (integer)
- unit_price (decimal(10,2))
- tax_rate (decimal(5,2))
- tax_amount (decimal(10,2))
- discount_amount (decimal(10,2), default 0)
- subtotal (decimal(10,2))
- total (decimal(10,2))
- created_at, updated_at
```

**order_status_history**
```sql
- id (bigint, PK)
- order_id (FK)
- status (string)
- comment (text, nullable)
- notify_customer (boolean, default false)
- user_id (FK, nullable) -- Admin que cambió el estado
- created_at
```

**invoices**
```sql
- id (bigint, PK)
- order_id (FK)
- invoice_number (string, unique)
- invoice_date (date)
- subtotal (decimal(10,2))
- tax_total (decimal(10,2))
- grand_total (decimal(10,2))
- pdf_path (string, nullable)
- created_at, updated_at
```

#### 8. ENVÍOS

**carriers**
```sql
- id (bigint, PK)
- name (string)
- display_name (string)
- delay (string) -- Ej: "24-48 horas"
- is_active (boolean, default true)
- position (integer, default 0)
- created_at, updated_at
```

**shipping_zones**
```sql
- id (bigint, PK)
- name (string)
- is_active (boolean, default true)
- created_at, updated_at
```

**shipping_zone_countries**
```sql
- shipping_zone_id (FK)
- country_code (string, 2 chars)
- PRIMARY KEY (shipping_zone_id, country_code)
```

**shipping_rates**
```sql
- id (bigint, PK)
- carrier_id (FK)
- shipping_zone_id (FK)
- calculation_type (enum: by_weight, by_price, flat_rate)
- min_value (decimal(10,2), nullable)
- max_value (decimal(10,2), nullable)
- cost (decimal(10,2))
- is_free (boolean, default false)
- created_at, updated_at
```

**shipments**
```sql
- id (bigint, PK)
- order_id (FK)
- carrier_id (FK)
- tracking_number (string, nullable)
- shipped_at (timestamp, nullable)
- delivered_at (timestamp, nullable)
- created_at, updated_at
```

#### 9. CMS Y CONTENIDO

**pages**
```sql
- id (bigint, PK)
- title (string)
- slug (string, unique)
- content (text)
- is_active (boolean, default true)
- meta_title (string, nullable)
- meta_description (string, nullable)
- created_at, updated_at
```

**banners**
```sql
- id (bigint, PK)
- title (string)
- image (string)
- link (string, nullable)
- position (string) -- home_main, sidebar, etc.
- display_order (integer, default 0)
- start_date (datetime, nullable)
- end_date (datetime, nullable)
- is_active (boolean, default true)
- created_at, updated_at
```

**menu_items**
```sql
- id (bigint, PK)
- parent_id (FK self, nullable)
- menu_location (string) -- header, footer
- title (string)
- url (string, nullable)
- target (enum: _self, _blank)
- position (integer, default 0)
- is_active (boolean, default true)
- created_at, updated_at
```

#### 10. MULTILENGUAJE Y MULTIMONEDA

**languages**
```sql
- id (bigint, PK)
- code (string, unique) -- es, en, fr
- name (string)
- is_active (boolean, default true)
- is_default (boolean, default false)
- created_at, updated_at
```

**currencies**
```sql
- id (bigint, PK)
- code (string, unique) -- EUR, USD
- symbol (string) -- €, $
- exchange_rate (decimal(10,6), default 1.000000)
- is_active (boolean, default true)
- is_default (boolean, default false)
- created_at, updated_at
```

**translations**
```sql
- id (bigint, PK)
- translatable_type (string) -- Product, Category, etc.
- translatable_id (bigint)
- language_code (string)
- field_name (string) -- name, description, etc.
- field_value (text)
- created_at, updated_at
- INDEX (translatable_type, translatable_id, language_code)
```

#### 11. CONFIGURACIÓN

**settings**
```sql
- id (bigint, PK)
- key (string, unique)
- value (text, nullable)
- type (enum: string, integer, boolean, json)
- group (string) -- general, shop, payment, shipping
- created_at, updated_at
```

---

## ESTRUCTURA DE DIRECTORIOS

```
app/
├── Actions/                     # Single-purpose action classes
│   ├── Cart/
│   │   ├── AddItemToCart.php
│   │   ├── RemoveItemFromCart.php
│   │   └── UpdateCartItemQuantity.php
│   ├── Order/
│   │   ├── CreateOrder.php
│   │   ├── CancelOrder.php
│   │   └── ProcessPayment.php
│   └── Product/
│       └── UpdateProductStock.php
│
├── Console/
│   └── Commands/
│
├── DataTransferObjects/         # DTOs para transferencia de datos
│   ├── CartItemDTO.php
│   ├── OrderDTO.php
│   └── ProductDTO.php
│
├── Events/
│   ├── OrderCreated.php
│   ├── OrderStatusChanged.php
│   ├── ProductOutOfStock.php
│   └── PaymentProcessed.php
│
├── Exceptions/
│   ├── InsufficientStockException.php
│   ├── InvalidCouponException.php
│   └── PaymentFailedException.php
│
├── Http/
│   ├── Controllers/
│   │   ├── Shop/                # Frontend controllers
│   │   │   ├── CartController.php
│   │   │   ├── CheckoutController.php
│   │   │   ├── HomeController.php
│   │   │   ├── ProductController.php
│   │   │   ├── CategoryController.php
│   │   │   └── AccountController.php
│   │   └── Api/                 # API controllers (futuro)
│   │       └── V1/
│   │
│   ├── Middleware/
│   │   ├── SetLanguage.php
│   │   ├── SetCurrency.php
│   │   └── EnsureCartExists.php
│   │
│   └── Requests/
│       ├── Cart/
│       │   └── AddToCartRequest.php
│       ├── Checkout/
│       │   ├── ShippingAddressRequest.php
│       │   └── PlaceOrderRequest.php
│       └── Product/
│
├── Listeners/
│   ├── SendOrderConfirmationEmail.php
│   ├── UpdateProductStockOnOrder.php
│   ├── CreateInvoice.php
│   └── NotifyAdminNewOrder.php
│
├── Mail/
│   ├── OrderConfirmation.php
│   ├── OrderStatusChanged.php
│   └── WelcomeCustomer.php
│
├── Models/
│   ├── Address.php
│   ├── Attribute.php
│   ├── AttributeValue.php
│   ├── Banner.php
│   ├── Brand.php
│   ├── Carrier.php
│   ├── Cart.php
│   ├── CartItem.php
│   ├── Category.php
│   ├── Coupon.php
│   ├── Currency.php
│   ├── CustomerGroup.php
│   ├── Invoice.php
│   ├── Language.php
│   ├── MenuItem.php
│   ├── Order.php
│   ├── OrderItem.php
│   ├── OrderStatusHistory.php
│   ├── Page.php
│   ├── PriceRule.php
│   ├── Product.php
│   ├── ProductCombination.php
│   ├── ProductImage.php
│   ├── Setting.php
│   ├── Shipment.php
│   ├── ShippingRate.php
│   ├── ShippingZone.php
│   ├── StockMovement.php
│   ├── Tax.php
│   ├── Translation.php
│   └── User.php
│
├── Policies/
│   ├── OrderPolicy.php
│   └── AddressPolicy.php
│
├── Providers/
│   ├── AppServiceProvider.php
│   ├── EventServiceProvider.php
│   ├── RouteServiceProvider.php
│   └── PaymentServiceProvider.php  # Para registrar payment drivers
│
├── Repositories/                # Implementación del patrón Repository
│   ├── Contracts/
│   │   ├── ProductRepositoryInterface.php
│   │   ├── OrderRepositoryInterface.php
│   │   └── CartRepositoryInterface.php
│   ├── Eloquent/
│   │   ├── ProductRepository.php
│   │   ├── OrderRepository.php
│   │   └── CartRepository.php
│
├── Services/                    # Lógica de negocio
│   ├── Cart/
│   │   ├── CartService.php
│   │   └── CartCalculator.php
│   ├── Checkout/
│   │   └── CheckoutService.php
│   ├── Order/
│   │   ├── OrderService.php
│   │   └── InvoiceService.php
│   ├── Payment/
│   │   ├── PaymentServiceInterface.php
│   │   ├── PayPalPaymentService.php
│   │   └── BankTransferPaymentService.php
│   ├── Pricing/
│   │   ├── PriceCalculator.php
│   │   ├── TaxCalculator.php
│   │   └── DiscountCalculator.php
│   ├── Product/
│   │   ├── ProductService.php
│   │   └── StockService.php
│   ├── Shipping/
│   │   ├── ShippingServiceInterface.php
│   │   └── ShippingCalculator.php
│   └── Translation/
│       └── TranslationService.php
│
└── View/
    └── Components/              # Blade components
        ├── Product/
        │   ├── Card.php
        │   ├── Grid.php
        │   └── QuickView.php
        ├── Cart/
        │   ├── MiniCart.php
        │   └── Summary.php
        └── Layout/
            ├── Header.php
            └── Footer.php

database/
├── factories/
├── migrations/
│   ├── 2025_01_01_000001_create_users_table.php
│   ├── 2025_01_01_000002_create_customer_groups_table.php
│   ├── 2025_01_01_000003_create_addresses_table.php
│   ├── ... (todas las tablas)
│   └── 2025_01_01_000050_create_translations_table.php
└── seeders/
    ├── DatabaseSeeder.php
    ├── CategorySeeder.php
    ├── BrandSeeder.php
    ├── ProductSeeder.php
    ├── TaxSeeder.php
    ├── CurrencySeeder.php
    ├── LanguageSeeder.php
    └── SettingSeeder.php

resources/
├── css/
│   └── app.css
├── js/
│   ├── app.js
│   └── components/
│       ├── cart.js
│       ├── product-quick-view.js
│       └── checkout.js
└── views/
    ├── components/
    │   ├── layout/
    │   │   ├── header.blade.php
    │   │   ├── footer.blade.php
    │   │   └── navigation.blade.php
    │   ├── product/
    │   │   ├── card.blade.php
    │   │   ├── grid.blade.php
    │   │   ├── attributes.blade.php
    │   │   └── price.blade.php
    │   ├── cart/
    │   │   ├── mini-cart.blade.php
    │   │   └── summary.blade.php
    │   └── forms/
    │       ├── input.blade.php
    │       ├── select.blade.php
    │       └── checkbox.blade.php
    │
    ├── layouts/
    │   ├── app.blade.php
    │   ├── guest.blade.php
    │   └── checkout.blade.php
    │
    ├── shop/
    │   ├── home.blade.php
    │   ├── products/
    │   │   ├── index.blade.php      # Listado de productos
    │   │   └── show.blade.php        # Detalle de producto
    │   ├── categories/
    │   │   └── show.blade.php
    │   ├── cart/
    │   │   └── index.blade.php
    │   ├── checkout/
    │   │   ├── index.blade.php
    │   │   ├── shipping.blade.php
    │   │   ├── payment.blade.php
    │   │   └── confirmation.blade.php
    │   └── account/
    │       ├── orders/
    │       │   ├── index.blade.php
    │       │   └── show.blade.php
    │       ├── addresses/
    │       │   ├── index.blade.php
    │       │   ├── create.blade.php
    │       │   └── edit.blade.php
    │       └── profile.blade.php
    │
    └── emails/
        ├── orders/
        │   ├── confirmation.blade.php
        │   └── status-changed.blade.php
        └── welcome.blade.php

routes/
├── web.php                      # Rutas del frontend
├── api.php                      # API (futuro)
└── console.php

tests/
├── Feature/
│   ├── Cart/
│   │   ├── AddToCartTest.php
│   │   └── RemoveFromCartTest.php
│   ├── Checkout/
│   │   └── CheckoutProcessTest.php
│   ├── Order/
│   │   ├── CreateOrderTest.php
│   │   └── OrderStatusTest.php
│   └── Product/
│       └── ProductAvailabilityTest.php
└── Unit/
    ├── Services/
    │   ├── PriceCalculatorTest.php
    │   ├── TaxCalculatorTest.php
    │   └── StockServiceTest.php
    └── Models/
        └── ProductTest.php

config/
├── shop.php                     # Configuración de la tienda
├── payment.php                  # Configuración de pagos
└── shipping.php                 # Configuración de envíos
```

---

## PLAN DE IMPLEMENTACIÓN POR FASES

### FASE 1: FUNDAMENTOS (Semana 1-2)

#### 1.1 Setup Inicial
- [x] Instalación de Laravel 12
- [ ] Configuración de Docker
- [ ] Setup de Tailwind CSS 4
- [ ] Configuración de Pest para testing
- [ ] Git flow y .gitignore

#### 1.2 Autenticación
- [ ] Instalar Laravel Breeze
- [ ] Personalizar vistas de login/registro
- [ ] Agregar campos adicionales a users (first_name, last_name, phone)
- [ ] Implementar compra como invitado

#### 1.3 Estructura Base
- [ ] Crear estructura de directorios
- [ ] Configurar service providers
- [ ] Setup de repositorios base
- [ ] Configurar helpers y utilities

---

### FASE 2: CATÁLOGO DE PRODUCTOS (Semana 3-4)

#### 2.1 Base de Datos
- [ ] Migración: brands
- [ ] Migración: categories (con soporte jerárquico)
- [ ] Migración: products
- [ ] Migración: product_images
- [ ] Migración: product_categories
- [ ] Migración: attributes y attribute_values
- [ ] Migración: product_combinations
- [ ] Migración: product_combination_values

#### 2.2 Modelos y Relaciones
- [ ] Modelo Brand con relaciones
- [ ] Modelo Category con nested sets o closure table
- [ ] Modelo Product con:
  - Relación con Brand
  - Relación con Categories (many-to-many)
  - Relación con Images
  - Relación con Combinations
  - Scopes (active, featured, inStock)
  - Accessors para precio final
- [ ] Modelo ProductCombination
- [ ] Modelos Attribute y AttributeValue

#### 2.3 Servicios
- [ ] ProductService (CRUD, búsqueda, filtros)
- [ ] StockService (gestión de inventario)
- [ ] ProductRepository

#### 2.4 Frontend - Catálogo
- [ ] Layout principal (app.blade.php)
- [ ] Componente Header con búsqueda
- [ ] Componente Footer
- [ ] Home page con productos destacados
- [ ] Listado de productos con filtros:
  - Por categoría
  - Por marca
  - Por precio
  - Por atributos
  - Ordenamiento
- [ ] Ficha de producto:
  - Galería de imágenes
  - Selector de combinaciones
  - Información de stock
  - Precio con impuestos
  - Botón añadir al carrito
- [ ] Página de categoría
- [ ] Búsqueda de productos

#### 2.5 Testing
- [ ] Tests unitarios para ProductService
- [ ] Tests de feature para listado de productos
- [ ] Tests de feature para detalle de producto

---

### FASE 3: CARRITO DE COMPRAS (Semana 5)

#### 3.1 Base de Datos
- [ ] Migración: carts
- [ ] Migración: cart_items

#### 3.2 Modelos
- [ ] Modelo Cart con relaciones
- [ ] Modelo CartItem

#### 3.3 Servicios
- [ ] CartService:
  - addItem()
  - removeItem()
  - updateQuantity()
  - clear()
  - getTotal()
  - applyDiscount()
- [ ] CartCalculator:
  - calculateSubtotal()
  - calculateTax()
  - calculateShipping()
  - calculateGrandTotal()
- [ ] CartRepository

#### 3.4 Frontend - Carrito
- [ ] Mini-cart (dropdown en header)
- [ ] Página de carrito completa
- [ ] Actualización de cantidades (AJAX)
- [ ] Botón eliminar producto
- [ ] Resumen de totales
- [ ] Botón proceder al checkout

#### 3.5 Testing
- [ ] Tests para CartService
- [ ] Tests para añadir/quitar productos
- [ ] Tests de cálculo de totales

---

### FASE 4: PRECIOS, IMPUESTOS Y DESCUENTOS (Semana 6)

#### 4.1 Base de Datos
- [ ] Migración: taxes
- [ ] Migración: price_rules
- [ ] Migración: coupons
- [ ] Migración: coupon_usage
- [ ] Migración: customer_groups

#### 4.2 Modelos
- [ ] Modelo Tax
- [ ] Modelo PriceRule
- [ ] Modelo Coupon
- [ ] Modelo CustomerGroup

#### 4.3 Servicios
- [ ] TaxCalculator:
  - Cálculo por país/región
  - Aplicación de reglas de impuestos
- [ ] PriceCalculator:
  - Precio base
  - Aplicación de price rules
  - Precio final con impuestos
- [ ] DiscountCalculator:
  - Validación de cupones
  - Cálculo de descuentos
  - Aplicación de reglas de carrito

#### 4.4 Frontend
- [ ] Campo para aplicar cupón en carrito
- [ ] Mostrar descuentos aplicados
- [ ] Mostrar desglose de impuestos

#### 4.5 Testing
- [ ] Tests para TaxCalculator
- [ ] Tests para PriceCalculator
- [ ] Tests para cupones

---

### FASE 5: CHECKOUT Y PEDIDOS (Semana 7-8)

#### 5.1 Base de Datos
- [ ] Migración: addresses
- [ ] Migración: orders
- [ ] Migración: order_items
- [ ] Migración: order_status_history
- [ ] Migración: invoices

#### 5.2 Modelos
- [ ] Modelo Address
- [ ] Modelo Order con relaciones
- [ ] Modelo OrderItem
- [ ] Modelo OrderStatusHistory
- [ ] Modelo Invoice

#### 5.3 Servicios
- [ ] CheckoutService:
  - validateCart()
  - validateAddress()
  - validateShipping()
  - validatePayment()
- [ ] OrderService:
  - createOrder()
  - updateStatus()
  - cancelOrder()
  - getOrdersByUser()
- [ ] InvoiceService:
  - generateInvoice()
  - generatePDF()

#### 5.4 Acciones
- [ ] CreateOrder action
- [ ] ProcessPayment action

#### 5.5 Frontend - Checkout
- [ ] Checkout multi-paso:
  1. Login/Guest
  2. Dirección de envío
  3. Dirección de facturación
  4. Método de envío
  5. Método de pago
  6. Resumen y confirmación
- [ ] Validaciones client-side
- [ ] Progress indicator
- [ ] Página de confirmación
- [ ] Página de error de pago

#### 5.6 Frontend - Cuenta de Usuario
- [ ] Mi cuenta - Dashboard
- [ ] Mis pedidos (listado)
- [ ] Detalle de pedido
- [ ] Mis direcciones (CRUD)
- [ ] Descargar factura (PDF)

#### 5.7 Testing
- [ ] Tests del flujo completo de checkout
- [ ] Tests de creación de orden
- [ ] Tests de estados de orden

---

### FASE 6: ENVÍOS (Semana 9)

#### 6.1 Base de Datos
- [ ] Migración: carriers
- [ ] Migración: shipping_zones
- [ ] Migración: shipping_zone_countries
- [ ] Migración: shipping_rates
- [ ] Migración: shipments

#### 6.2 Modelos
- [ ] Modelo Carrier
- [ ] Modelo ShippingZone
- [ ] Modelo ShippingRate
- [ ] Modelo Shipment

#### 6.3 Servicios
- [ ] ShippingCalculator:
  - calculateShipping() por peso
  - calculateShipping() por precio
  - calculateShipping() flat rate
  - getAvailableCarriers()
- [ ] Interface ShippingServiceInterface
- [ ] Implementaciones de carriers específicos

#### 6.4 Frontend
- [ ] Selector de método de envío en checkout
- [ ] Mostrar tiempo de entrega estimado
- [ ] Cálculo dinámico de costos

#### 6.5 Testing
- [ ] Tests de cálculo de envío
- [ ] Tests de carriers disponibles

---

### FASE 7: PAGOS (Semana 10)

#### 7.1 Servicios
- [ ] Interface PaymentServiceInterface
- [ ] BankTransferPaymentService
- [ ] PayPalPaymentService (integración)
- [ ] PaymentServiceProvider (registro de drivers)

#### 7.2 Frontend
- [ ] Selector de método de pago
- [ ] Formulario para transferencia bancaria
- [ ] Integración de PayPal SDK
- [ ] Página de procesamiento de pago
- [ ] Página de pago exitoso
- [ ] Página de pago fallido

#### 7.3 Testing
- [ ] Tests de procesamiento de pagos (mock)
- [ ] Tests de estados de pago

---

### FASE 8: EMAILS Y NOTIFICACIONES (Semana 11)

#### 8.1 Mailables
- [ ] OrderConfirmation
- [ ] OrderStatusChanged
- [ ] WelcomeCustomer
- [ ] InvoiceGenerated
- [ ] PasswordReset (si no viene con Breeze)

#### 8.2 Eventos y Listeners
- [ ] Event: OrderCreated → SendOrderConfirmationEmail
- [ ] Event: OrderStatusChanged → NotifyCustomer
- [ ] Event: OrderCreated → NotifyAdminNewOrder
- [ ] Event: OrderCreated → CreateInvoice
- [ ] Event: OrderCreated → UpdateProductStock

#### 8.3 Templates de Email
- [ ] Diseño de emails con Tailwind/inline CSS
- [ ] Plantillas responsivas

#### 8.4 Testing
- [ ] Tests de envío de emails
- [ ] Tests de listeners

---

### FASE 9: CMS Y CONTENIDO (Semana 12)

#### 9.1 Base de Datos
- [ ] Migración: pages
- [ ] Migración: banners
- [ ] Migración: menu_items

#### 9.2 Modelos
- [ ] Modelo Page
- [ ] Modelo Banner
- [ ] Modelo MenuItem

#### 9.3 Frontend
- [ ] Página CMS (render dinámico)
- [ ] Home con banners
- [ ] Menús dinámicos (header/footer)
- [ ] Páginas estáticas (Términos, Privacidad, etc.)

---

### FASE 10: MULTILENGUAJE Y MULTIMONEDA (Semana 13)

#### 10.1 Base de Datos
- [ ] Migración: languages
- [ ] Migración: currencies
- [ ] Migración: translations

#### 10.2 Modelos
- [ ] Modelo Language
- [ ] Modelo Currency
- [ ] Modelo Translation
- [ ] Trait Translatable para modelos

#### 10.3 Servicios
- [ ] TranslationService
- [ ] CurrencyConverter

#### 10.4 Middleware
- [ ] SetLanguage
- [ ] SetCurrency

#### 10.5 Frontend
- [ ] Selector de idioma
- [ ] Selector de moneda
- [ ] Formateo de precios según moneda
- [ ] Traducción de contenido

#### 10.6 Seeders
- [ ] LanguageSeeder (es, en, fr)
- [ ] CurrencySeeder (EUR, USD, GBP)

---

### FASE 11: SEO Y OPTIMIZACIÓN (Semana 14)

#### 11.1 SEO
- [ ] URLs amigables (slug en todos los modelos)
- [ ] Meta tags dinámicos
- [ ] Sitemap.xml
- [ ] Robots.txt
- [ ] Schema.org markup:
  - Product
  - BreadcrumbList
  - Organization
  - Offer

#### 11.2 Performance
- [ ] Cache de productos
- [ ] Cache de categorías
- [ ] Cache de configuración
- [ ] Eager loading en listados
- [ ] Optimización de imágenes
- [ ] CDN para assets (opcional)

#### 11.3 Frontend
- [ ] Lazy loading de imágenes
- [ ] Minificación de CSS/JS
- [ ] Optimización de Tailwind (purge)

---

### FASE 12: GESTIÓN DE STOCK AVANZADA (Semana 15)

#### 12.1 Base de Datos
- [ ] Migración: stock_movements

#### 12.2 Servicios
- [ ] StockService avanzado:
  - recordMovement()
  - reserveStock()
  - releaseStock()
  - adjustStock()
  - getStockHistory()

#### 12.3 Eventos
- [ ] Event: ProductOutOfStock → NotifyAdmin
- [ ] Event: ProductLowStock → NotifyAdmin

#### 12.4 Testing
- [ ] Tests de reserva de stock
- [ ] Tests de ajustes de stock

---

### FASE 13: CONFIGURACIÓN GLOBAL (Semana 16)

#### 13.1 Base de Datos
- [ ] Migración: settings

#### 13.2 Sistema de Settings
- [ ] Modelo Setting
- [ ] SettingService para leer/escribir configuración
- [ ] Cache de settings

#### 13.3 Configuraciones
- [ ] General: nombre de tienda, logo, email
- [ ] Shop: modo mantenimiento, registros habilitados
- [ ] Emails: configuración SMTP
- [ ] Pagos: activar/desactivar métodos
- [ ] Envíos: peso por defecto, dimensiones

---

### FASE 14: TESTING COMPLETO (Semana 17)

#### 14.1 Tests de Integración
- [ ] Test del flujo completo: navegación → carrito → checkout → pago
- [ ] Tests de diferentes escenarios de pago
- [ ] Tests de aplicación de cupones
- [ ] Tests de cálculo de envíos

#### 14.2 Tests de Seguridad
- [ ] XSS prevention
- [ ] SQL injection prevention
- [ ] CSRF protection
- [ ] Mass assignment protection

#### 14.3 Cobertura
- [ ] Objetivo: >80% code coverage
- [ ] Revisión de tests faltantes

---

### FASE 15: MÓDULOS Y EXTENSIBILIDAD (Semana 18)

#### 15.1 Sistema de Eventos
- [ ] Documentar todos los eventos disponibles
- [ ] Crear eventos adicionales para hooks

#### 15.2 Service Providers Personalizados
- [ ] PaymentServiceProvider (registrar nuevos métodos de pago)
- [ ] ShippingServiceProvider (registrar nuevos carriers)

#### 15.3 Documentación
- [ ] Guía de creación de módulos
- [ ] Ejemplos de extensión

---

### FASE 16: POLISH Y PRODUCCIÓN (Semana 19-20)

#### 16.1 UX/UI
- [ ] Revisión de accesibilidad (WCAG)
- [ ] Mensajes de error amigables
- [ ] Loading states
- [ ] Feedback visual (toasts, alertas)
- [ ] Animaciones sutiles

#### 16.2 Seguridad
- [ ] Rate limiting en rutas sensibles
- [ ] Validación de inputs
- [ ] Sanitización de datos
- [ ] Políticas de contraseñas

#### 16.3 Deploy
- [ ] Configuración de entornos (staging, production)
- [ ] Setup de CI/CD
- [ ] Backups automáticos
- [ ] Monitoring y logs

#### 16.4 Seeders de Producción
- [ ] Seeders para datos iniciales
- [ ] Productos de ejemplo
- [ ] Categorías de ejemplo

#### 16.5 Documentación Final
- [ ] README completo
- [ ] Guía de instalación
- [ ] Guía de configuración
- [ ] API documentation (si aplica)

---

## SERVICIOS Y REPOSITORIOS

### Patrón Repository

**Interface:**
```php
interface ProductRepositoryInterface
{
    public function find(int $id);
    public function findBySlug(string $slug);
    public function all();
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getFeatured(int $limit = 10);
    public function getNew(int $limit = 10);
    public function getBestSellers(int $limit = 10);
    public function search(string $query);
    public function filterByCategory(int $categoryId);
}
```

**Implementación:**
```php
class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(protected Product $model) {}

    // Implementación de métodos...
}
```

**Registro en Service Provider:**
```php
$this->app->bind(
    ProductRepositoryInterface::class,
    ProductRepository::class
);
```

### Servicios Principales

#### 1. ProductService
```php
class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected StockService $stockService,
        protected PriceCalculator $priceCalculator
    ) {}

    public function getProductWithPrice(int $id, ?int $userId = null): ProductDTO
    {
        $product = $this->productRepository->find($id);
        $price = $this->priceCalculator->calculate($product, $userId);

        return new ProductDTO($product, $price);
    }

    public function checkAvailability(Product $product, int $quantity): bool
    {
        return $this->stockService->isAvailable($product, $quantity);
    }
}
```

#### 2. CartService
```php
class CartService
{
    public function addItem(Cart $cart, Product $product, int $quantity): void
    {
        // Verificar stock
        if (!$this->stockService->isAvailable($product, $quantity)) {
            throw new InsufficientStockException();
        }

        // Añadir o actualizar item
        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price_snapshot' => $product->base_price,
            ]);
        }
    }

    public function getTotal(Cart $cart): float
    {
        return $this->cartCalculator->calculateGrandTotal($cart);
    }
}
```

#### 3. OrderService
```php
class OrderService
{
    public function createFromCart(Cart $cart, array $data): Order
    {
        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => $cart->user_id,
                'order_number' => $this->generateOrderNumber(),
                // ... más datos
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->price_snapshot,
                    // ... más datos
                ]);

                // Reducir stock
                $this->stockService->recordMovement(
                    $item->product,
                    $item->quantity,
                    'out',
                    'Order #' . $order->order_number
                );
            }

            event(new OrderCreated($order));

            DB::commit();

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
```

---

## FRONTEND (BLADE + TAILWIND)

### Configuración de Tailwind 4

**tailwind.config.js:**
```js
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#f0f9ff',
          500: '#0ea5e9',
          600: '#0284c7',
          700: '#0369a1',
        },
        secondary: {
          500: '#6366f1',
          600: '#4f46e5',
        }
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
```

### Componentes Blade Reutilizables

#### Layout Principal
```blade
<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') }}</title>

    @stack('meta')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <x-layout.header />

    <main>
        {{ $slot }}
    </main>

    <x-layout.footer />

    @stack('scripts')
</body>
</html>
```

#### Componente Product Card
```blade
<!-- resources/views/components/product/card.blade.php -->
@props(['product'])

<div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
    <a href="{{ route('products.show', $product->slug) }}">
        <img
            src="{{ $product->primary_image }}"
            alt="{{ $product->name }}"
            class="w-full h-64 object-cover rounded-t-lg"
        >
    </a>

    <div class="p-4">
        <h3 class="font-semibold text-gray-900 mb-2">
            <a href="{{ route('products.show', $product->slug) }}" class="hover:text-primary-600">
                {{ $product->name }}
            </a>
        </h3>

        <x-product.price :product="$product" />

        <button
            type="button"
            class="mt-4 w-full bg-primary-600 text-white py-2 px-4 rounded-md hover:bg-primary-700"
            onclick="addToCart({{ $product->id }})"
        >
            Añadir al carrito
        </button>
    </div>
</div>
```

#### Componente Price
```blade
<!-- resources/views/components/product/price.blade.php -->
@props(['product'])

<div class="flex items-center gap-2">
    @if($product->has_discount)
        <span class="text-lg font-bold text-primary-600">
            {{ $product->discounted_price_formatted }}
        </span>
        <span class="text-sm text-gray-500 line-through">
            {{ $product->base_price_formatted }}
        </span>
    @else
        <span class="text-lg font-bold text-gray-900">
            {{ $product->base_price_formatted }}
        </span>
    @endif
</div>
```

### Páginas Principales

#### Home
```blade
<x-layouts.app>
    <x-slot:title>Home - {{ config('app.name') }}</x-slot>

    <!-- Hero Banner -->
    <section class="bg-primary-600 text-white py-20">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">
                Bienvenido a LaraShop
            </h1>
            <p class="text-xl mb-8">
                Los mejores productos al mejor precio
            </p>
            <a href="{{ route('products.index') }}" class="bg-white text-primary-600 px-8 py-3 rounded-lg font-semibold">
                Ver productos
            </a>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="container mx-auto px-4 py-12">
        <h2 class="text-3xl font-bold mb-8">Productos destacados</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
                <x-product.card :product="$product" />
            @endforeach
        </div>
    </section>

    <!-- New Products -->
    <section class="bg-gray-100 py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-8">Novedades</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($newProducts as $product)
                    <x-product.card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.app>
```

---

## SISTEMA DE MÓDULOS

### Arquitectura de Eventos

Los módulos/plugins se conectan al sistema mediante eventos de Laravel:

**Eventos Disponibles:**
```php
// Productos
ProductCreated
ProductUpdated
ProductDeleted
ProductOutOfStock
ProductLowStock

// Carrito
ItemAddedToCart
ItemRemovedFromCart
CartUpdated
CartCleared

// Pedidos
OrderCreated
OrderStatusChanged
OrderCancelled
OrderRefunded
PaymentProcessed
PaymentFailed

// Envíos
ShipmentCreated
ShipmentDispatched
ShipmentDelivered
```

### Ejemplo de Módulo de Pago Personalizado

**1. Crear Service Provider:**
```php
namespace App\Modules\StripePayment;

use Illuminate\Support\ServiceProvider;
use App\Services\Payment\PaymentServiceInterface;

class StripePaymentServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('payment.stripe', StripePaymentService::class);
    }

    public function boot()
    {
        // Registrar el driver de pago
        app('payment.manager')->extend('stripe', function ($app) {
            return $app->make(StripePaymentService::class);
        });
    }
}
```

**2. Implementar Service:**
```php
namespace App\Modules\StripePayment;

use App\Services\Payment\PaymentServiceInterface;

class StripePaymentService implements PaymentServiceInterface
{
    public function process(Order $order, array $data): PaymentResult
    {
        // Lógica de Stripe
    }

    public function refund(Order $order, float $amount): bool
    {
        // Lógica de reembolso
    }
}
```

---

## TESTING

### Estructura de Tests

```php
// tests/Feature/Cart/AddToCartTest.php
test('user can add product to cart', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock_quantity' => 10]);

    actingAs($user)
        ->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($user->cart->items)->toHaveCount(1);
    expect($user->cart->items->first()->quantity)->toBe(2);
});

test('cannot add out of stock product to cart', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock_quantity' => 0]);

    actingAs($user)
        ->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ])
        ->assertSessionHasErrors('product_id');
});
```

### Coverage Objetivo
- Servicios: >90%
- Repositories: >85%
- Controllers: >75%
- Global: >80%

---

## SEGURIDAD

### Checklist de Seguridad

- [ ] **Autenticación:** Laravel Breeze con rate limiting
- [ ] **Autorización:** Policies para Orders, Addresses
- [ ] **CSRF Protection:** Habilitado por defecto
- [ ] **XSS Prevention:** Blade escapa por defecto `{{ }}`
- [ ] **SQL Injection:** Usar Query Builder y Eloquent
- [ ] **Mass Assignment:** Definir `$fillable` o `$guarded`
- [ ] **Validación:** Form Requests en todos los endpoints
- [ ] **Sanitización:** Validar y limpiar inputs
- [ ] **HTTPS:** Forzar en producción
- [ ] **Headers de Seguridad:** CSP, X-Frame-Options, etc.
- [ ] **Rate Limiting:** En login, registro, checkout
- [ ] **Password Hashing:** Bcrypt por defecto
- [ ] **Sensitive Data:** No loggear datos de pago

---

## CONFIGURACIÓN INICIAL

### Archivos de Configuración

**config/shop.php:**
```php
return [
    'name' => env('SHOP_NAME', 'LaraShop'),
    'email' => env('SHOP_EMAIL', 'info@larashop.com'),
    'currency' => env('SHOP_CURRENCY', 'EUR'),
    'locale' => env('SHOP_LOCALE', 'es'),
    'tax_included' => env('SHOP_TAX_INCLUDED', true),
    'guest_checkout' => env('SHOP_GUEST_CHECKOUT', true),
    'low_stock_threshold' => env('SHOP_LOW_STOCK_THRESHOLD', 5),
];
```

**config/payment.php:**
```php
return [
    'default' => env('PAYMENT_GATEWAY', 'bank_transfer'),

    'gateways' => [
        'bank_transfer' => [
            'enabled' => true,
            'name' => 'Transferencia Bancaria',
        ],
        'paypal' => [
            'enabled' => env('PAYPAL_ENABLED', false),
            'mode' => env('PAYPAL_MODE', 'sandbox'),
            'client_id' => env('PAYPAL_CLIENT_ID'),
            'secret' => env('PAYPAL_SECRET'),
        ],
    ],
];
```

---

## DEPENDENCIAS ADICIONALES

### Composer
```bash
composer require intervention/image         # Manipulación de imágenes
composer require spatie/laravel-sluggable   # Slugs automáticos
composer require barryvdh/laravel-dompdf    # Generación de PDFs
composer require spatie/laravel-translatable # Traducciones
```

### NPM
```bash
npm install alpinejs                        # Interactividad ligera
npm install @tailwindcss/forms              # Estilos de formularios
npm install @tailwindcss/typography         # Tipografía
```

---

## PRÓXIMOS PASOS

1. Revisar y aprobar este plan
2. Comenzar con Fase 1: Fundamentos
3. Crear migraciones de base de datos (Fase 2)
4. Implementar modelos y relaciones
5. Desarrollar servicios core
6. Construir frontend con Blade y Tailwind

---

**Última actualización:** 19 de diciembre de 2025
**Versión del documento:** 1.0
