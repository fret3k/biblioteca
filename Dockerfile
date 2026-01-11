FROM php:8.2-apache

# Enable Apache modules
RUN a2enmod rewrite

# Install dependencies for PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev

# Install PHP extensions: PDO MySQL (legacy), MySQLi (legacy), and PDO PGSQL (Supabase)
RUN docker-php-ext-install pdo pdo_mysql mysqli pdo_pgsql

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Configure Apache for the custom MVC structure
RUN echo '<Directory "/var/www/html">\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
    </Directory>' > /etc/apache2/conf-available/custom-mvc.conf \
    && a2enconf custom-mvc

EXPOSE 80
