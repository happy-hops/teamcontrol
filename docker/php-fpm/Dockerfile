FROM php:8.5-fpm-trixie

LABEL org.opencontainers.image.authors="matthias@buesing.de"

WORKDIR /var/www

COPY --from=ghcr.io/php/pie:bin /pie /usr/local/bin/pie
COPY --from=composer:2.10 /usr/bin/composer /usr/bin/composer

RUN <<-EOF
  set -eux
  export DEBIAN_FRONTEND=noninteractive
  apt-get update
  apt-get install -y --no-install-recommends \
    unzip curl less vim \
    $PHPIZE_DEPS \
    libicu-dev libpq-dev libzip-dev;
  pie install --no-interaction apcu/apcu;
  pie install --no-interaction xdebug/xdebug;
  docker-php-ext-install -j"$(nproc)" intl pdo_mysql zip;
  rm -rf /var/lib/apt/lists/*;
EOF

RUN set -eux; \
  cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"; \
  { \
    echo "memory_limit=1024M"; \
    echo "xdebug.mode=develop,debug"; \
    echo "xdebug.client_host=host.docker.internal"; \
    echo "xdebug.start_with_request=yes"; \
  } > /usr/local/etc/php/conf.d/zz-tune.ini;

COPY ./composer.json ./composer.lock ./symfony.lock ./
RUN composer install --prefer-dist --no-scripts --no-autoloader
COPY . .
RUN composer dump-autoload --optimize && composer run-script auto-scripts
