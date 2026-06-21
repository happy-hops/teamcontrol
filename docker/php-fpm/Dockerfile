FROM php:8.4-fpm-trixie

ARG TZ=Europe/Berlin
ARG GID=2001

LABEL org.opencontainers.image.authors="matthias@buesing.de"

RUN set -eux; \
  apt-get update; \
  apt-get install -y --no-install-recommends \
    libxml2-dev libicu-dev libzip-dev \
    default-mysql-client \
    unzip curl wget git less vim apt-transport-https; \
  docker-php-ext-configure intl; \
  docker-php-ext-configure opcache --enable-opcache; \
  docker-php-ext-install -j"$(nproc)" iconv intl pdo pdo_mysql zip xml opcache pcntl sockets; \
  pecl install apcu xdebug; \
  docker-php-ext-enable apcu xdebug; \
  curl -fsSL https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer; \
  rm -rf /var/lib/apt/lists/*;

RUN set -eux; \
  cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"; \
  { \
    echo "date.timezone=${TZ}"; \
    echo "memory_limit=1024M"; \
    echo "xdebug.mode=develop,debug"; \
    echo "xdebug.client_host=host.docker.internal"; \
    echo "xdebug.start_with_request=yes"; \
  } > /usr/local/etc/php/conf.d/zz-tune.ini; \
  ln -snf "/usr/share/zoneinfo/${TZ}" /etc/localtime && echo "${TZ}" > /etc/timezone;

WORKDIR /var/www
