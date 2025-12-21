# LaraShop - Project Context for Gemini

## Project Overview

**LaraShop** is a comprehensive e-commerce platform built on **Laravel 12**, designed to replicate the core functionality of PrestaShop 9 using a modern, scalable, and maintainable architecture. It uses a **Mobile-First** approach with Blade templates and Tailwind CSS.

### Key Technologies
-   **Framework:** Laravel 12 (PHP 8.2+)
-   **Frontend:** Blade Templates, Tailwind CSS 4, Alpine.js
-   **Database:** MySQL (primary), SQLite (testing/quickstart)
-   **Environment:** Docker (via Docker Compose)
-   **Asset Bundling:** Vite
-   **Testing:** Pest
-   **Authentication:** Laravel Breeze

---

## Architecture & Design Patterns

The project follows a **Clean Architecture** approach, separating responsibilities into distinct layers.

### Layers
1.  **Presentation:** Controllers, Blade Views, View Components.
2.  **Application:** Services, Actions, DTOs.
3.  **Domain:** Models, Business Logic, Events.
4.  **Infrastructure:** Repositories, External Services.

### Key Directories
-   `app/Actions`: Single-purpose classes for specific business tasks (e.g., `CreateOrder`).
-   `app/DataTransferObjects` (DTOs): Structured objects for passing data between layers (e.g., `ProductDTO`).
-   `app/Services`: Core business logic (e.g., `CartService`, `PriceCalculator`).
-   `app/Repositories`: Database abstraction layer (e.g., `ProductRepository`).
-   `app/Models`: Eloquent models (e.g., `Product`, `Order`).
-   `app/View/Components`: Reusable UI logic.
-   `tests/Feature` & `tests/Unit`: Pest test suites.

### Strict Conventions
-   **Service Pattern:** Heavy business logic **must** reside in `app/Services`, not Controllers.
-   **Repository Pattern:** All database queries should go through Repositories, not called directly in Controllers (except for simple CRUD where acceptable).
-   **Type Hinting:** Use strict typing for all method arguments and return types.
-   **Events:** Use Laravel Events for side effects (e.g., sending emails, updating stock) to ensure extensibility.
-   **DTOs:** Use DTOs for complex data structures instead of associative arrays.

---

## Operational Commands

### Setup & Installation
```bash
# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Setup Environment
cp .env.example .env
php artisan key:generate

# Database Setup
php artisan migrate
php artisan db:seed
```

### Development
```bash
# Start all dev services (Server, Queue, Logs, Vite)
composer dev

# Start only Vite (Frontend)
npm run dev

# Run Database Migrations
php artisan migrate

# Reset Database & Seed
php artisan migrate:fresh --seed
```

### Testing
```bash
# Run all tests
composer test

# Run specific test file
php artisan test tests/Feature/ProductTest.php

# Run with filter
php artisan test --filter name_of_test
```

### Code Generation
```bash
# Create a comprehensive set for a model
php artisan make:model Product -mf # Model + Migration + Factory

# Create a Service (Manual creation required, follow structure)
# Create a Repository (Manual creation required, follow structure)
```

---

## Database Schema Highlights

-   **Products:** `products`, `product_combinations`, `attributes`, `categories`, `brands`.
-   **Orders:** `orders`, `order_items`, `order_status_history`.
-   **Cart:** `carts`, `cart_items`.
-   **Users:** `users` (extended with profile fields), `customer_groups`, `addresses`.

---

## Current Status & Roadmap

The project is structured into **16 Phases**.
*   **Current Focus:** Foundation & Product Catalog (Phases 1-2).
*   **Upcoming:** Cart, Pricing, Checkout.

Refer to `docs/PLAN_MAESTRO.md` for the detailed roadmap and `docs/GUIA_INICIO.md` for setup details.
