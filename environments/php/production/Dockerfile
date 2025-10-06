ARG PHP_VERSION=8.4-fpm-alpine
ARG PHP_COMPOSER_VERSION=latest

FROM composer:${PHP_COMPOSER_VERSION} AS composer-stage

FROM php:${PHP_VERSION}

RUN apk add --no-cache \
    linux-headers \
    postgresql-dev \
    postgresql-libs \
    libzip-dev \
    zip unzip  \
    libzip \
    git curl bash \
    nodejs npm \
    supervisor \
    $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-install -j$(nproc) pdo_pgsql pdo_mysql zip intl exif \
    && docker-php-ext-enable redis intl exif \
    && apk del postgresql-dev libzip-dev linux-headers

COPY --from=composer-stage /usr/bin/composer /usr/bin/composer

COPY environments/php/production/php.ini /usr/local/etc/php/conf.d/99-php.ini
COPY environments/php/production/supervisor/conf.d/ /etc/supervisor/conf.d/

COPY environments/php/production/healthcheck.sh /usr/local/bin/healthcheck.sh
RUN chmod +x /usr/local/bin/healthcheck.sh


WORKDIR /var/www/html

COPY . .

RUN git config --global --add safe.directory /var/www/html

RUN if [ -f composer.json ] && [ -f composer.lock ]; then \
      composer install; \
    else \
      echo "Skipping composer install (no composer.json or composer.lock found)"; \
    fi

RUN \
  if [ -d storage ]; then \
    chown -R www-data:www-data storage; \
  else \
    echo "storage/ not found, skipping chown"; \
  fi && \
  if [ -d bootstrap/cache ]; then \
    chown -R www-data:www-data bootstrap/cache; \
  else \
    echo "bootstrap/cache/ not found, skipping chown"; \
  fi
