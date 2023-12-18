# server/authentication/Dockerfile

# Use the official PHP 8.2 Apache base image
FROM php:8.2-apache

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Install additional dependencies and tools
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpq-dev \
        libzip-dev \
        unzip \
        libonig-dev \
        libxml2-dev \
        default-mysql-client \
        curl \
        libmemcached-dev \
        libz-dev \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \
        libssl-dev \
        libmcrypt-dev \
        libpq5 \
        libpq-dev \
        supervisor \
        nano \
        zip unzip \
        libssh2-1-dev \
        openssl \
        libcurl4-openssl-dev \
    && docker-php-ext-install pdo_mysql mysqli pdo_pgsql zip mbstring xml

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.6.6

# Copy only the Composer files and install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader

# Copy the authentication application into the container at /var/www/html
COPY . .

# Run Composer dump-autoload and optimize
RUN composer dump-autoload --optimize

# Set the permissions for the storage directory
RUN chown -R www-data:www-data storage
RUN chmod -R 775 storage
RUN chmod 644 public/.htaccess
RUN chmod 644 apache-config/authentication.conf

# Cleanup Unnecessary Files
RUN apt-get remove -y libpq-dev libzip-dev libonig-dev libxml2-dev \
    && apt-get autoremove -y \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Create Apache error log directory
RUN mkdir -p /var/log/apache2

# Set ServerName and Apache document root
RUN echo "ServerName laravel-app.local" >> /etc/apache2/apache2.conf

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# mod_rewrite for URL rewrite and mod_headers for
# .htaccess extra headers like Access-Control-Allow-Origin-
RUN a2enmod rewrite headers

# Enable Apache mod_rewrite and mod_proxy
RUN a2enmod rewrite proxy proxy_http

# Copy the Apache configuration file for authentication
COPY apache-config/authentication.conf /etc/apache2/sites-available/authentication.conf

# Enable Apache site configuration
RUN a2ensite authentication.conf

# Allow the container to rewrite Apache configurations
RUN usermod -u 1000 www-data

# Restart Apache to apply changes
RUN service apache2 restart

# Expose port 80 for web traffic
EXPOSE 80

# Specify the command to run on container start
CMD ["apache2-foreground"]
