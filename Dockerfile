# syntax=docker/dockerfile:1

# Dokploy terminates TLS for cylo.knackline.com and proxies HTTP to port 80.
FROM dunglas/frankenphp:1-php8.5-trixie AS php

RUN install-php-extensions \
        bcmath \
        intl \
        opcache \
        pcntl \
        pdo_mysql \
        redis \
        zip \
    && cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && printf '%s\n' \
        'memory_limit=256M' \
        'opcache.enable=1' \
        'opcache.validate_timestamps=0' \
        > "$PHP_INI_DIR/conf.d/app.ini"

FROM php AS vendor

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
        --no-dev \
        --no-interaction \
        --no-scripts \
        --prefer-dist \
        --no-progress

COPY . .

ENV APP_ENV=production \
    APP_KEY=base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=

RUN composer dump-autoload --optimize --no-dev \
    && php artisan package:discover --ansi --no-interaction

FROM vendor AS assets

COPY --from=node:22-bookworm /usr/local/bin/node /usr/local/bin/node
COPY --from=node:22-bookworm /usr/local/bin/npm /usr/local/bin/npm
COPY --from=node:22-bookworm /usr/local/bin/npx /usr/local/bin/npx
COPY --from=node:22-bookworm /usr/local/lib/node_modules /usr/local/lib/node_modules

RUN npm ci

ARG VITE_APP_NAME=Cylo
ENV VITE_APP_NAME=${VITE_APP_NAME}

RUN npm run build

FROM php AS runtime

WORKDIR /app

ENV SERVER_NAME=:80 \
    APP_NAME=Cylo \
    APP_ENV=production \
    APP_DEBUG=false \
    APP_URL=https://cylo.knackline.com \
    LOG_CHANNEL=stderr

COPY --from=vendor /app /app
COPY --from=assets /app/public/build /app/public/build

COPY <<'EOF' /usr/local/bin/docker-entrypoint
#!/bin/sh
set -eu

cd /app

mkdir -p \
    storage/app/public \
    storage/app/private \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

php artisan storage:link --force --no-interaction

if [ "$#" -eq 0 ]; then
    set -- frankenphp run --config /etc/frankenphp/Caddyfile --adapter caddyfile
fi

if [ "$1" = "frankenphp" ]; then
    attempt=0

    until php artisan migrate --force --no-interaction; do
        attempt=$((attempt + 1))

        if [ "$attempt" -ge 30 ]; then
            echo "Migrations failed." >&2
            exit 1
        fi

        echo "Waiting for the database..." >&2
        sleep 2
    done

    php artisan config:cache --no-interaction
    php artisan view:cache --no-interaction
fi

exec "$@"
EOF

RUN chmod +x /usr/local/bin/docker-entrypoint

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=120s --retries=3 \
    CMD curl -f http://127.0.0.1/up || exit 1

ENTRYPOINT ["docker-entrypoint"]
CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile", "--adapter", "caddyfile"]
