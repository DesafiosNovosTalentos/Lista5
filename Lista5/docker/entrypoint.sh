#!/bin/bash
set -e

cd /var/www/html

echo "==> Removendo caches contaminados..."
rm -f bootstrap/cache/packages.php \
      bootstrap/cache/services.php \
      bootstrap/cache/config.php \
      bootstrap/cache/routes-v7.php \
      bootstrap/cache/events.php

echo "==> Limpando caches do Laravel..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true

echo "==> Aguardando banco de dados em $DB_HOST:$DB_PORT..."
for i in {1..60}; do
    if php -r "try { new PDO('pgsql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_DATABASE', '$DB_USERNAME', '$DB_PASSWORD'); echo 'OK'; } catch (Exception \$e) { exit(1); }" 2>/dev/null | grep -q OK; then
        echo "==> Banco respondeu!"
        break
    fi
    echo "    Tentativa $i/60..."
    sleep 2
done

echo "==> Regenerando cache de pacotes (sem dev deps)..."
php artisan package:discover --ansi || true

echo "==> Rodando migrations..."
php artisan migrate --force || echo "Migrate falhou ou já estava em dia"

echo "==> Cacheando configurações pra produção..."
php artisan config:cache
php artisan route:cache

echo "==> Iniciando Apache..."
exec "$@"