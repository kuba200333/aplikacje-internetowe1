FROM php:8.4-cli
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    libicu-dev \
    libpng-dev \
    libonig-dev \
    sqlite3 \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
    gd \
    intl \
    mbstring \
    pdo_sqlite

WORKDIR /var/www/html

CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]