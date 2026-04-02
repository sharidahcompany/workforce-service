FROM ghcr.io/sharidahcompany/ceo-app-base:latest

# Set working directory
WORKDIR /var/www

# Copy app code
COPY . .

# Install Laravel dependencies
# RUN composer install --no-dev --optimize-autoloader
RUN composer install

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000

# Start PHP-FPM
CMD ["sh", "-lc", "chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache && chmod -R 775 /var/www/storage /var/www/bootstrap/cache && exec php-fpm"]


