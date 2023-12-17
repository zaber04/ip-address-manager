# server/gateway/Dockerfile

# Use the official PHP 8.2 Apache base image
FROM php:8.2-apache

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Copy the gateway application into the container at /var/www/html
COPY . .

# Set the permissions for the storage directory
RUN chown -R www-data:www-data storage
RUN chmod -R 775 storage

# Enable Apache mod_rewrite and mod_proxy
RUN a2enmod rewrite proxy proxy_http

# Copy the Apache configuration file for gateway
COPY apache-config/gateway.conf /etc/apache2/sites-available/gateway.conf

# Enable Apache site configuration
RUN a2ensite gateway.conf

# Allow the container to rewrite Apache configurations
RUN usermod -u 1000 www-data

# Restart Apache to apply changes
RUN service apache2 restart

# Expose port 80 for web traffic
EXPOSE 80

# Specify the command to run on container start
CMD ["apache2-foreground"]
