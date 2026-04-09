FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies sistem
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libonig-dev

# Bersihkan cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install ekstensi PHP (PDO MySQL tetap butuh sebagai driver untuk koneksi remote)
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Expose port 9000 untuk PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]