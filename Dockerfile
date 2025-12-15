FROM php:8.3-cli-alpine
WORKDIR /app
COPY . /app

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader || true

EXPOSE 8080
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
