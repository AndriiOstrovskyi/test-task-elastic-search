FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    supervisor \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY --chown=www-data:www-data . /var/www

# Create cache directory and set permissions
RUN mkdir -p /var/www/bootstrap/cache && \
    chown -R www-data:www-data /var/www && \
    chmod -R 775 /var/www/bootstrap/cache

# Check if composer.json exists
RUN ls -la /var/www

# Check Composer version
RUN composer --version

# Install Laravel dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Expose port 9000 and start php-fpm server
EXPOSE 9000

# Copy supervisor configuration
COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf  

CMD ["supervisord", "-n"]
