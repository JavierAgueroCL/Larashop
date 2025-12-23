# Laravel Shop

## Installation

```bash
docker exec larashop-app-1 composer install
```

```bash
docker exec larashop-app-1 php artisan migrate
```

```bash
docker exec larashop-app-1 php artisan db:seed
```

```bash
docker exec larashop-app-1 php artisan app:update-exchange-rates
```

```bash
docker exec larashop-app-1 php artisan db:wipe && docker exec larashop-app-1 php artisan migrate --seed
```