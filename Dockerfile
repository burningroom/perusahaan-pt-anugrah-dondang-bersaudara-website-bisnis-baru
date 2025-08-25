# ────────────────────────────────────────────────────────────────
# Stage 1: Composer deps (no dev) on PHP 8.3 + intl/soap/zip present
# ────────────────────────────────────────────────────────────────
FROM php:8.3-cli AS vendor
WORKDIR /app

# Composer binary
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install build deps so Composer platform checks pass
RUN apt-get update \
  && apt-get install -y --no-install-recommends \
       libicu-dev libxml2-dev zlib1g-dev libzip-dev git unzip \
  && docker-php-ext-install -j"$(nproc)" intl soap zip \
  && rm -rf /var/lib/apt/lists/*

# Copy composer manifests (lock is optional)
COPY composer.json composer.lock* ./

# Install prod deps
# If lock exists, install from it; if not, update to resolve & create lock.
# (Composer automatically falls back to update when lock is missing.)
RUN composer install --no-dev --no-interaction --prefer-dist --no-ansi --no-progress --no-scripts

# ────────────────────────────────────────────────────────────────
# Stage 2: Frontend build with Bun (Vite)
# ────────────────────────────────────────────────────────────────
FROM oven/bun:1 AS frontend
WORKDIR /app

COPY package.json bun.lockb* ./
RUN bun install --frozen-lockfile

# app sources needed for build
COPY resources ./resources
COPY public ./public

# copy likely config files individually; use what you actually have
COPY vite.config.js ./
COPY postcss.config.js ./
COPY tailwind.config.js ./

# Filament vendor CSS
COPY --from=vendor /app/vendor /app/vendor

RUN bun run build

# ────────────────────────────────────────────────────────────────
# Stage 3: Runtime (PHP-FPM 8.3 on Alpine)
# ────────────────────────────────────────────────────────────────
FROM php:8.3-fpm-alpine AS runtime

RUN apk add --no-cache bash tzdata icu-libs \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
       icu-dev oniguruma-dev libzip-dev postgresql-dev \
       freetype-dev libjpeg-turbo-dev libpng-dev libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" intl soap bcmath pcntl zip gd pdo_mysql pdo_pgsql opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# OPcache for prod
RUN { \
  echo 'opcache.enable=1'; \
  echo 'opcache.enable_cli=1'; \
  echo 'opcache.jit=1205'; \
  echo 'opcache.jit_buffer_size=64M'; \
  echo 'opcache.memory_consumption=256'; \
  echo 'opcache.interned_strings_buffer=16'; \
  echo 'opcache.max_accelerated_files=20000'; \
  echo 'opcache.validate_timestamps=0'; \
} > /usr/local/etc/php/conf.d/opcache.ini

ENV APP_ENV=production TZ=UTC
WORKDIR /var/www/html

# App code
COPY . /var/www/html

# Vendor from composer stage
COPY --from=vendor /app/vendor /var/www/html/vendor

# Built assets from Bun/Vite (dist -> public/build)
COPY --from=frontend /app/public/build /var/www/html/public/build

# place start script as root with execute bit in one step
COPY --chmod=0755 ./docker/start-prod.sh /usr/local/bin/start-prod

# Non-root user + perms
RUN addgroup -g 1000 www && adduser -D -G www -u 1000 www \
    && chown -R www:www storage bootstrap/cache \
    && find storage -type d -exec chmod 775 {} \; \
    && find storage -type f -exec chmod 664 {} \; \
    && chmod -R 775 bootstrap/cache

USER www

EXPOSE 9000
CMD ["start-prod"]
