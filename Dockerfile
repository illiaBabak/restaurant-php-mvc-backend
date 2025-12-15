FROM php:8.3-cli-alpine

WORKDIR /app
COPY . /app

# ✅ ext-mongodb
RUN apk add --no-cache $PHPIZE_DEPS openssl-dev \
  && pecl install mongodb \
  && docker-php-ext-enable mongodb \
  && apk del $PHPIZE_DEPS

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

EXPOSE 8080
CMD ["sh", "-lc", "php -S 0.0.0.0:${PORT:-8080} -t public -d log_errors=1 -d error_log=php://stdout"]
