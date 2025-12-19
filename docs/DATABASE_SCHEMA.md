# Base de Datos - LaraShop
## Esquema Completo y Relaciones

---

## ÍNDICE

1. [Convenciones](#convenciones)
2. [Diagrama de Relaciones](#diagrama-de-relaciones)
3. [Tablas por Módulo](#tablas-por-módulo)
4. [Índices y Optimizaciones](#índices-y-optimizaciones)
5. [Triggers y Procedimientos](#triggers-y-procedimientos)
6. [Ejemplo de Migraciones](#ejemplo-de-migraciones)

---

## CONVENCIONES

### Nomenclatura
- Tablas: plural, snake_case (ej: `product_images`)
- Columnas: snake_case (ej: `created_at`)
- Foreign Keys: `{tabla_singular}_id` (ej: `product_id`)
- Pivot tables: orden alfabético (ej: `product_categories`)

### Tipos de Datos
- IDs: `bigint unsigned`
- Precios: `decimal(10,2)`
- Porcentajes: `decimal(5,2)`
- Textos cortos: `string` (varchar 255)
- Textos largos: `text`
- Booleanos: `boolean` (tinyint 1)
- Fechas: `timestamp` o `date` según contexto

### Campos Estándar
Todas las tablas principales incluyen:
- `id` (bigint unsigned, PK, auto_increment)
- `created_at` (timestamp)
- `updated_at` (timestamp)
- `deleted_at` (timestamp, nullable) - para soft deletes cuando aplica

---

## DIAGRAMA DE RELACIONES

### Módulo de Usuarios

```
┌─────────────────┐
│ customer_groups │
└────────┬────────┘
         │ 1:N
         │
┌────────▼────────┐       ┌──────────────┐
│     users       │──────▶│  addresses   │
└────────┬────────┘  1:N  └──────────────┘
         │
         │ 1:N
         │
    ┌────▼─────┬──────────┬──────────┐
    │          │          │          │
┌───▼───┐  ┌──▼───┐  ┌───▼────┐  ┌──▼─────┐
│ carts │  │orders│  │addresses│  │coupon_ │
│       │  │      │  │         │  │usage   │
└───────┘  └──────┘  └─────────┘  └────────┘
```

### Módulo de Productos

```
┌────────────┐
│   brands   │
└─────┬──────┘
      │ 1:N
      │
┌─────▼──────────────────────────────────────┐
│              products                      │
└──┬───────┬──────────┬──────────┬──────────┘
   │       │          │          │
   │ 1:N   │ 1:N      │ N:N      │ 1:1
   │       │          │          │
┌──▼────┐ ┌▼────────┐ ┌▼───────┐ ┌▼─────┐
│product││product_  ││product_ ││taxes │
│images ││combina- ││categor- │└──────┘
│       ││tions    ││ies     │
└───────┘ └┬────────┘ └────────┘
           │ N:N
           │
      ┌────▼──────────┐
      │ attribute_    │
      │ values        │
      └───────────────┘
```

### Módulo de Pedidos

```
┌────────┐
│ users  │
└───┬────┘
    │ 1:N
┌───▼──────┐    ┌─────────────────┐
│  orders  │───▶│  order_items    │
└┬──┬──┬──┬┘1:N └─────────────────┘
 │  │  │  │
 │  │  │  └──────┐
 │  │  │         │ 1:N
 │  │  │    ┌────▼─────────────┐
 │  │  │    │ order_status_    │
 │  │  │    │ history          │
 │  │  │    └──────────────────┘
 │  │  │
 │  │  └──────┐ 1:1
 │  │    ┌────▼──────┐
 │  │    │ invoices  │
 │  │    └───────────┘
 │  │
 │  └──────┐ 1:1
 │    ┌────▼──────┐
 │    │ shipments │
 │    └───────────┘
 │
 └──────┐ 2:N (billing + shipping)
   ┌────▼─────────┐
   │  addresses   │
   └──────────────┘
```

### Módulo de Carrito

```
┌────────┐
│ users  │
└───┬────┘
    │ 1:1
┌───▼────┐       ┌──────────────┐
│ carts  │──────▶│  cart_items  │
└────────┘  1:N  └──────┬───────┘
                        │ N:1
                  ┌─────▼────────┐
                  │   products   │
                  └──────────────┘
```

---

## TABLAS POR MÓDULO

### 1. USUARIOS Y AUTENTICACIÓN

#### users
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NULL,
    is_guest BOOLEAN DEFAULT FALSE,
    customer_group_id BIGINT UNSIGNED NULL,
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
    last_login_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,

    INDEX idx_email (email),
    INDEX idx_customer_group (customer_group_id),
    FOREIGN KEY (customer_group_id) REFERENCES customer_groups(id) ON DELETE SET NULL
);
```

#### customer_groups
```sql
CREATE TABLE customer_groups (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    discount_percentage DECIMAL(5,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### addresses
```sql
CREATE TABLE addresses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    address_type ENUM('shipping', 'billing', 'both') DEFAULT 'both',
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    company VARCHAR(150) NULL,
    address_line_1 VARCHAR(255) NOT NULL,
    address_line_2 VARCHAR(255) NULL,
    city VARCHAR(100) NOT NULL,
    state_province VARCHAR(100) NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    country_code CHAR(2) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_user (user_id),
    INDEX idx_country (country_code),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

---

### 2. CATÁLOGO

#### brands
```sql
CREATE TABLE brands (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    logo VARCHAR(255) NULL,
    description TEXT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_slug (slug),
    INDEX idx_active (is_active)
);
```

#### categories
```sql
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id BIGINT UNSIGNED NULL,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    description TEXT NULL,
    image VARCHAR(255) NULL,
    position INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    meta_title VARCHAR(255) NULL,
    meta_description VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_parent (parent_id),
    INDEX idx_slug (slug),
    INDEX idx_active (is_active),
    INDEX idx_position (position),
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE
);
```

#### products
```sql
CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    brand_id BIGINT UNSIGNED NULL,
    sku VARCHAR(100) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    short_description TEXT NULL,
    description TEXT NULL,
    is_digital BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    is_featured BOOLEAN DEFAULT FALSE,
    base_price DECIMAL(10,2) NOT NULL,
    cost_price DECIMAL(10,2) NULL,
    tax_id BIGINT UNSIGNED NULL,
    weight DECIMAL(8,2) NULL COMMENT 'en gramos',
    width DECIMAL(8,2) NULL COMMENT 'en cm',
    height DECIMAL(8,2) NULL COMMENT 'en cm',
    depth DECIMAL(8,2) NULL COMMENT 'en cm',
    stock_quantity INT DEFAULT 0,
    low_stock_threshold INT DEFAULT 5,
    has_combinations BOOLEAN DEFAULT FALSE,
    meta_title VARCHAR(255) NULL,
    meta_description VARCHAR(255) NULL,
    views_count INT UNSIGNED DEFAULT 0,
    sales_count INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,

    INDEX idx_sku (sku),
    INDEX idx_slug (slug),
    INDEX idx_brand (brand_id),
    INDEX idx_tax (tax_id),
    INDEX idx_active (is_active),
    INDEX idx_featured (is_featured),
    INDEX idx_price (base_price),
    FULLTEXT idx_search (name, short_description, description),

    FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE SET NULL,
    FOREIGN KEY (tax_id) REFERENCES taxes(id) ON DELETE SET NULL
);
```

#### product_images
```sql
CREATE TABLE product_images (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    position INT DEFAULT 0,
    alt_text VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_product (product_id),
    INDEX idx_primary (is_primary),
    INDEX idx_position (position),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
```

#### product_categories (pivot)
```sql
CREATE TABLE product_categories (
    product_id BIGINT UNSIGNED NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,

    PRIMARY KEY (product_id, category_id),
    INDEX idx_category (category_id),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);
```

---

### 3. ATRIBUTOS Y COMBINACIONES

#### attributes
```sql
CREATE TABLE attributes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    position INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_slug (slug)
);
```

#### attribute_values
```sql
CREATE TABLE attribute_values (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    attribute_id BIGINT UNSIGNED NOT NULL,
    value VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL,
    color_hex VARCHAR(7) NULL COMMENT 'Para atributo tipo color',
    position INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_attribute (attribute_id),
    INDEX idx_slug (slug),
    UNIQUE KEY unique_attribute_value (attribute_id, slug),
    FOREIGN KEY (attribute_id) REFERENCES attributes(id) ON DELETE CASCADE
);
```

#### product_combinations
```sql
CREATE TABLE product_combinations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    sku VARCHAR(100) NOT NULL UNIQUE,
    price_impact DECIMAL(10,2) DEFAULT 0.00,
    weight_impact DECIMAL(8,2) DEFAULT 0.00,
    stock_quantity INT DEFAULT 0,
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_product (product_id),
    INDEX idx_sku (sku),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
```

#### product_combination_values (pivot)
```sql
CREATE TABLE product_combination_values (
    combination_id BIGINT UNSIGNED NOT NULL,
    attribute_value_id BIGINT UNSIGNED NOT NULL,

    PRIMARY KEY (combination_id, attribute_value_id),
    INDEX idx_attribute_value (attribute_value_id),
    FOREIGN KEY (combination_id) REFERENCES product_combinations(id) ON DELETE CASCADE,
    FOREIGN KEY (attribute_value_id) REFERENCES attribute_values(id) ON DELETE CASCADE
);
```

---

### 4. STOCK

#### stock_movements
```sql
CREATE TABLE stock_movements (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NULL,
    product_combination_id BIGINT UNSIGNED NULL,
    movement_type ENUM('in', 'out', 'adjustment', 'reserved', 'released') NOT NULL,
    quantity INT NOT NULL,
    reason VARCHAR(255) NULL,
    reference_type VARCHAR(100) NULL COMMENT 'Order, Return, Adjustment',
    reference_id BIGINT UNSIGNED NULL,
    user_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_product (product_id),
    INDEX idx_combination (product_combination_id),
    INDEX idx_type (movement_type),
    INDEX idx_created (created_at),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (product_combination_id) REFERENCES product_combinations(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);
```

---

### 5. PRECIOS E IMPUESTOS

#### taxes
```sql
CREATE TABLE taxes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    rate DECIMAL(5,2) NOT NULL COMMENT 'Porcentaje: 21.00 para 21%',
    country_code CHAR(2) NULL,
    state_province VARCHAR(100) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_country (country_code),
    INDEX idx_active (is_active)
);
```

#### price_rules
```sql
CREATE TABLE price_rules (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    rule_type ENUM('customer_group', 'quantity', 'date_range') NOT NULL,
    customer_group_id BIGINT UNSIGNED NULL,
    product_id BIGINT UNSIGNED NULL,
    category_id BIGINT UNSIGNED NULL,
    min_quantity INT NULL,
    discount_type ENUM('percentage', 'fixed') NOT NULL,
    discount_value DECIMAL(10,2) NOT NULL,
    start_date DATE NULL,
    end_date DATE NULL,
    priority INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_type (rule_type),
    INDEX idx_customer_group (customer_group_id),
    INDEX idx_product (product_id),
    INDEX idx_category (category_id),
    INDEX idx_dates (start_date, end_date),
    INDEX idx_active (is_active),
    FOREIGN KEY (customer_group_id) REFERENCES customer_groups(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);
```

#### coupons
```sql
CREATE TABLE coupons (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    description TEXT NULL,
    discount_type ENUM('percentage', 'fixed', 'free_shipping') NOT NULL,
    discount_value DECIMAL(10,2) NOT NULL,
    min_purchase_amount DECIMAL(10,2) NULL,
    max_uses INT NULL COMMENT 'null = ilimitado',
    uses_count INT DEFAULT 0,
    max_uses_per_user INT NULL,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_code (code),
    INDEX idx_dates (start_date, end_date),
    INDEX idx_active (is_active)
);
```

#### coupon_usage
```sql
CREATE TABLE coupon_usage (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    coupon_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    order_id BIGINT UNSIGNED NOT NULL,
    used_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_coupon (coupon_id),
    INDEX idx_user (user_id),
    INDEX idx_order (order_id),
    FOREIGN KEY (coupon_id) REFERENCES coupons(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);
```

---

### 6. CARRITO

#### carts
```sql
CREATE TABLE carts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    session_id VARCHAR(255) NULL,
    coupon_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_user (user_id),
    INDEX idx_session (session_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (coupon_id) REFERENCES coupons(id) ON DELETE SET NULL
);
```

#### cart_items
```sql
CREATE TABLE cart_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cart_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    product_combination_id BIGINT UNSIGNED NULL,
    quantity INT NOT NULL,
    price_snapshot DECIMAL(10,2) NOT NULL COMMENT 'Precio al momento de agregar',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_cart (cart_id),
    INDEX idx_product (product_id),
    FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (product_combination_id) REFERENCES product_combinations(id) ON DELETE SET NULL
);
```

---

### 7. PEDIDOS

#### orders
```sql
CREATE TABLE orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    user_id BIGINT UNSIGNED NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_first_name VARCHAR(100) NOT NULL,
    customer_last_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NULL,

    billing_address_id BIGINT UNSIGNED NOT NULL,
    shipping_address_id BIGINT UNSIGNED NOT NULL,

    subtotal DECIMAL(10,2) NOT NULL,
    tax_total DECIMAL(10,2) NOT NULL,
    shipping_cost DECIMAL(10,2) NOT NULL,
    discount_total DECIMAL(10,2) DEFAULT 0.00,
    grand_total DECIMAL(10,2) NOT NULL,

    coupon_id BIGINT UNSIGNED NULL,
    coupon_discount DECIMAL(10,2) DEFAULT 0.00,

    payment_method VARCHAR(50) NOT NULL,
    payment_status ENUM('pending', 'processing', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    payment_transaction_id VARCHAR(255) NULL,

    shipping_method VARCHAR(50) NOT NULL,
    tracking_number VARCHAR(100) NULL,

    current_status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded') DEFAULT 'pending',
    notes TEXT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_order_number (order_number),
    INDEX idx_user (user_id),
    INDEX idx_email (customer_email),
    INDEX idx_status (current_status),
    INDEX idx_payment_status (payment_status),
    INDEX idx_created (created_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (billing_address_id) REFERENCES addresses(id),
    FOREIGN KEY (shipping_address_id) REFERENCES addresses(id),
    FOREIGN KEY (coupon_id) REFERENCES coupons(id) ON DELETE SET NULL
);
```

#### order_items
```sql
CREATE TABLE order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    product_combination_id BIGINT UNSIGNED NULL,
    product_name VARCHAR(255) NOT NULL COMMENT 'Snapshot',
    product_sku VARCHAR(100) NOT NULL COMMENT 'Snapshot',
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    tax_rate DECIMAL(5,2) NOT NULL,
    tax_amount DECIMAL(10,2) NOT NULL,
    discount_amount DECIMAL(10,2) DEFAULT 0.00,
    subtotal DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_order (order_id),
    INDEX idx_product (product_id),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT,
    FOREIGN KEY (product_combination_id) REFERENCES product_combinations(id) ON DELETE SET NULL
);
```

#### order_status_history
```sql
CREATE TABLE order_status_history (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    status VARCHAR(50) NOT NULL,
    comment TEXT NULL,
    notify_customer BOOLEAN DEFAULT FALSE,
    user_id BIGINT UNSIGNED NULL COMMENT 'Admin que cambió el estado',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_order (order_id),
    INDEX idx_created (created_at),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);
```

#### invoices
```sql
CREATE TABLE invoices (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    invoice_number VARCHAR(50) NOT NULL UNIQUE,
    invoice_date DATE NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    tax_total DECIMAL(10,2) NOT NULL,
    grand_total DECIMAL(10,2) NOT NULL,
    pdf_path VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_order (order_id),
    INDEX idx_invoice_number (invoice_number),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);
```

---

### 8. ENVÍOS

#### carriers
```sql
CREATE TABLE carriers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    display_name VARCHAR(150) NOT NULL,
    delay VARCHAR(100) NULL COMMENT 'Ej: 24-48 horas',
    is_active BOOLEAN DEFAULT TRUE,
    position INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_active (is_active)
);
```

#### shipping_zones
```sql
CREATE TABLE shipping_zones (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_active (is_active)
);
```

#### shipping_zone_countries (pivot)
```sql
CREATE TABLE shipping_zone_countries (
    shipping_zone_id BIGINT UNSIGNED NOT NULL,
    country_code CHAR(2) NOT NULL,

    PRIMARY KEY (shipping_zone_id, country_code),
    INDEX idx_country (country_code),
    FOREIGN KEY (shipping_zone_id) REFERENCES shipping_zones(id) ON DELETE CASCADE
);
```

#### shipping_rates
```sql
CREATE TABLE shipping_rates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    carrier_id BIGINT UNSIGNED NOT NULL,
    shipping_zone_id BIGINT UNSIGNED NOT NULL,
    calculation_type ENUM('by_weight', 'by_price', 'flat_rate') NOT NULL,
    min_value DECIMAL(10,2) NULL COMMENT 'Peso mínimo o precio mínimo',
    max_value DECIMAL(10,2) NULL COMMENT 'Peso máximo o precio máximo',
    cost DECIMAL(10,2) NOT NULL,
    is_free BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_carrier (carrier_id),
    INDEX idx_zone (shipping_zone_id),
    FOREIGN KEY (carrier_id) REFERENCES carriers(id) ON DELETE CASCADE,
    FOREIGN KEY (shipping_zone_id) REFERENCES shipping_zones(id) ON DELETE CASCADE
);
```

#### shipments
```sql
CREATE TABLE shipments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    carrier_id BIGINT UNSIGNED NOT NULL,
    tracking_number VARCHAR(100) NULL,
    shipped_at TIMESTAMP NULL,
    delivered_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_order (order_id),
    INDEX idx_tracking (tracking_number),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (carrier_id) REFERENCES carriers(id) ON DELETE RESTRICT
);
```

---

### 9. CMS

#### pages
```sql
CREATE TABLE pages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content TEXT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    meta_title VARCHAR(255) NULL,
    meta_description VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_slug (slug),
    INDEX idx_active (is_active)
);
```

#### banners
```sql
CREATE TABLE banners (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    image VARCHAR(255) NOT NULL,
    link VARCHAR(255) NULL,
    position VARCHAR(50) NOT NULL COMMENT 'home_main, sidebar, footer',
    display_order INT DEFAULT 0,
    start_date DATETIME NULL,
    end_date DATETIME NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_position (position),
    INDEX idx_active (is_active),
    INDEX idx_dates (start_date, end_date)
);
```

#### menu_items
```sql
CREATE TABLE menu_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id BIGINT UNSIGNED NULL,
    menu_location VARCHAR(50) NOT NULL COMMENT 'header, footer',
    title VARCHAR(100) NOT NULL,
    url VARCHAR(255) NULL,
    target ENUM('_self', '_blank') DEFAULT '_self',
    position INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_parent (parent_id),
    INDEX idx_location (menu_location),
    INDEX idx_position (position),
    INDEX idx_active (is_active),
    FOREIGN KEY (parent_id) REFERENCES menu_items(id) ON DELETE CASCADE
);
```

---

### 10. MULTILENGUAJE

#### languages
```sql
CREATE TABLE languages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code CHAR(2) NOT NULL UNIQUE COMMENT 'ISO 639-1',
    name VARCHAR(50) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_code (code),
    INDEX idx_active (is_active)
);
```

#### currencies
```sql
CREATE TABLE currencies (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code CHAR(3) NOT NULL UNIQUE COMMENT 'ISO 4217',
    symbol VARCHAR(10) NOT NULL,
    exchange_rate DECIMAL(10,6) DEFAULT 1.000000,
    is_active BOOLEAN DEFAULT TRUE,
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_code (code),
    INDEX idx_active (is_active)
);
```

#### translations
```sql
CREATE TABLE translations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    translatable_type VARCHAR(100) NOT NULL COMMENT 'Product, Category, etc',
    translatable_id BIGINT UNSIGNED NOT NULL,
    language_code CHAR(2) NOT NULL,
    field_name VARCHAR(50) NOT NULL COMMENT 'name, description, etc',
    field_value TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY unique_translation (translatable_type, translatable_id, language_code, field_name),
    INDEX idx_translatable (translatable_type, translatable_id),
    INDEX idx_language (language_code),
    FOREIGN KEY (language_code) REFERENCES languages(code) ON DELETE CASCADE
);
```

---

### 11. CONFIGURACIÓN

#### settings
```sql
CREATE TABLE settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(100) NOT NULL UNIQUE,
    value TEXT NULL,
    type ENUM('string', 'integer', 'boolean', 'json') DEFAULT 'string',
    `group` VARCHAR(50) NOT NULL COMMENT 'general, shop, payment, shipping',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_key (`key`),
    INDEX idx_group (`group`)
);
```

---

## ÍNDICES Y OPTIMIZACIONES

### Índices Clave

**Para Búsqueda de Productos:**
```sql
-- Índice FULLTEXT para búsqueda
ALTER TABLE products ADD FULLTEXT INDEX idx_search (name, short_description, description);

-- Índices compuestos para filtrado
CREATE INDEX idx_active_featured ON products(is_active, is_featured);
CREATE INDEX idx_active_price ON products(is_active, base_price);
```

**Para Rendimiento de Carrito:**
```sql
-- Índice compuesto para búsqueda de items
CREATE INDEX idx_cart_product ON cart_items(cart_id, product_id);
```

**Para Órdenes:**
```sql
-- Índices compuestos para dashboard de usuario
CREATE INDEX idx_user_status ON orders(user_id, current_status);
CREATE INDEX idx_user_date ON orders(user_id, created_at);
```

### Particionamiento (Opcional para Alto Volumen)

```sql
-- Particionar tabla de stock_movements por fecha
ALTER TABLE stock_movements
PARTITION BY RANGE (YEAR(created_at)) (
    PARTITION p2024 VALUES LESS THAN (2025),
    PARTITION p2025 VALUES LESS THAN (2026),
    PARTITION p2026 VALUES LESS THAN (2027),
    PARTITION p_future VALUES LESS THAN MAXVALUE
);
```

---

## TRIGGERS Y PROCEDIMIENTOS

### Trigger: Actualizar Stock en Producto

```sql
DELIMITER $$

CREATE TRIGGER update_product_stock_after_movement
AFTER INSERT ON stock_movements
FOR EACH ROW
BEGIN
    IF NEW.product_id IS NOT NULL THEN
        UPDATE products
        SET stock_quantity = stock_quantity +
            CASE
                WHEN NEW.movement_type IN ('in', 'released') THEN NEW.quantity
                WHEN NEW.movement_type IN ('out', 'reserved') THEN -NEW.quantity
                WHEN NEW.movement_type = 'adjustment' THEN NEW.quantity
            END
        WHERE id = NEW.product_id;
    END IF;

    IF NEW.product_combination_id IS NOT NULL THEN
        UPDATE product_combinations
        SET stock_quantity = stock_quantity +
            CASE
                WHEN NEW.movement_type IN ('in', 'released') THEN NEW.quantity
                WHEN NEW.movement_type IN ('out', 'reserved') THEN -NEW.quantity
                WHEN NEW.movement_type = 'adjustment' THEN NEW.quantity
            END
        WHERE id = NEW.product_combination_id;
    END IF;
END$$

DELIMITER ;
```

### Trigger: Incrementar Contador de Usos de Cupón

```sql
DELIMITER $$

CREATE TRIGGER increment_coupon_usage
AFTER INSERT ON coupon_usage
FOR EACH ROW
BEGIN
    UPDATE coupons
    SET uses_count = uses_count + 1
    WHERE id = NEW.coupon_id;
END$$

DELIMITER ;
```

---

## EJEMPLO DE MIGRACIONES

### Migración: Products

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('sku', 100)->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_digital')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->decimal('base_price', 10, 2);
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->foreignId('tax_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('weight', 8, 2)->nullable()->comment('en gramos');
            $table->decimal('width', 8, 2)->nullable()->comment('en cm');
            $table->decimal('height', 8, 2)->nullable()->comment('en cm');
            $table->decimal('depth', 8, 2)->nullable()->comment('en cm');
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->boolean('has_combinations')->default(false);
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('sales_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('sku');
            $table->index('slug');
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('base_price');
            $table->fullText(['name', 'short_description', 'description'], 'idx_search');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

### Migración: Orders

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_email');
            $table->string('customer_first_name', 100);
            $table->string('customer_last_name', 100);
            $table->string('customer_phone', 20)->nullable();

            $table->foreignId('billing_address_id')->constrained('addresses');
            $table->foreignId('shipping_address_id')->constrained('addresses');

            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_total', 10, 2);
            $table->decimal('shipping_cost', 10, 2);
            $table->decimal('discount_total', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2);

            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('coupon_discount', 10, 2)->default(0);

            $table->string('payment_method', 50);
            $table->enum('payment_status', ['pending', 'processing', 'completed', 'failed', 'refunded'])->default('pending');
            $table->string('payment_transaction_id')->nullable();

            $table->string('shipping_method', 50);
            $table->string('tracking_number', 100)->nullable();

            $table->enum('current_status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'])->default('pending');
            $table->text('notes')->nullable();

            $table->timestamps();

            // Índices
            $table->index('order_number');
            $table->index('user_id');
            $table->index('customer_email');
            $table->index('current_status');
            $table->index('payment_status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
```

---

## RESUMEN DE CONTEO DE TABLAS

| Módulo | Tablas |
|--------|--------|
| Usuarios y Autenticación | 3 |
| Catálogo | 4 + 1 pivot |
| Atributos y Combinaciones | 4 + 1 pivot |
| Stock | 1 |
| Precios e Impuestos | 4 |
| Carrito | 2 |
| Pedidos | 4 |
| Envíos | 4 + 1 pivot |
| CMS | 3 |
| Multilenguaje | 3 |
| Configuración | 1 |
| **TOTAL** | **34 tablas** |

---

**Última actualización:** 19 de diciembre de 2025
