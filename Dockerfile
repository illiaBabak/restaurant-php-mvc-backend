FROM php:8.3-cli

WORKDIR /app
COPY . /app

# Install CA certificates and build deps so mongodb extension is built with SSL support
RUN apt-get update \
 && apt-get install -y ca-certificates openssl libssl-dev pkg-config git build-essential \
 && update-ca-certificates \
  && pecl install mongodb \
  && docker-php-ext-enable mongodb \
 && apt-get purge -y --auto-remove libssl-dev pkg-config git build-essential \
 && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

EXPOSE 8080
CMD ["sh", "-lc", "php -S 0.0.0.0:${PORT:-8080} -t public -d log_errors=1 -d error_log=php://stdout"]
