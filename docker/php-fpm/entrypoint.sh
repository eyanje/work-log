# Migrate database. Force migrations to avoid a prompt.
php artisan migrate --force

php artisan optimize

php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

$1
