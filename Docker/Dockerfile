FROM php:8.3-fpm

LABEL maintainer="Javier Aguero javieraguerocl@gmail.com"

ENV ACCEPT_EULA=Y

ARG DEBIAN_FRONTEND=noninteractive

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libwebp-dev \
    locales \
    zip \
    libzip-dev \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    gnupg \
    gnupg2 \
    gnupg1

# Install MS ODBC Driver for SQL Server
RUN curl -fsSL https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor -o /usr/share/keyrings/microsoft-prod.gpg \
    && curl https://packages.microsoft.com/config/debian/12/prod.list | tee /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get -y --no-install-recommends install msodbcsql17 \
    && ACCEPT_EULA=Y apt-get install -y mssql-tools \
    && apt-get install -y libgssapi-krb5-2 \
    && apt-get -y --no-install-recommends install unixodbc-dev \
    && apt-get install -y nano

RUN pecl install sqlsrv \
    && pecl install pdo_sqlsrv \
    && echo "extension=pdo_sqlsrv.so" >> `php --ini | grep "Scan for additional .ini files" | sed -e "s|.*:\s*||"`/30-pdo_sqlsrv.ini \
    && echo "extension=sqlsrv.so" >> `php --ini | grep "Scan for additional .ini files" | sed -e "s|.*:\s*||"`/30-sqlsrv.ini

# OpenSSL for MSSQL Remote Connection Support
RUN apt-get install -y --no-install-recommends openssl \
    && sed -i 's,^\(MinProtocol[ ]*=\).*,\1'TLSv1.0',g' /etc/ssl/openssl.cnf \
    && sed -i 's,^\(CipherString[ ]*=\).*,\1'DEFAULT@SECLEVEL=1',g' /etc/ssl/openssl.cnf

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Copy OpenSSL CFG
COPY ./php/openssl.cnf /etc/ssl/openssl.cnf

# Install extensions
# RUN docker-php-ext-install mysql
RUN docker-php-ext-install pdo_mysql zip exif pcntl mysqli
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install gd

#MongoDB Extension Install
RUN pecl install mongodb \
    &&  echo "extension=mongodb.so" > $PHP_INI_DIR/conf.d/mongo.ini 

# Install composer 1
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \ && composer self-update --1

# Install composer 2
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
