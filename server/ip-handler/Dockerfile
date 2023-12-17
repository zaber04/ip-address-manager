# Dockerfile for ip-handler
FROM php:8.0-apache

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Copy the contents of the ip-handler directory into the container at /var/www/html
COPY . /var/www/html

# Enable Apache modules
RUN a2enmod rewrite

# Set permissions for the web server
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Copy the ip-handler-specific Apache configuration
COPY apache-config/000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable the site
RUN a2ensite 000-default

# Expose port 80 for Apache
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
