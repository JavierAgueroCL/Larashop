docker exec larashop-app-1 php artisan migrate:fresh --seed
docker exec larashop-app-1 php artisan cache:clear
docker exec larashop-app-1 php artisan config:clear
docker exec larashop-app-1 php artisan route:clear
docker exec larashop-app-1 php artisan view:clear
docker exec larashop-app-1 php artisan optimize:clear
