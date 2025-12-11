FROM php:8.4-fpm-bookworm

# Set working directory
WORKDIR /var/www

# Gunakan mirror Debian yang lebih cepat untuk Indonesia
# Jika masih error, coba uncomment baris di bawah untuk menggunakan mirror lokal
RUN sed -i 's|http://deb.debian.org|http://kartolo.sby.datautama.net.id|g' /etc/apt/sources.list.d/debian.sources 2>/dev/null || \
    sed -i 's|http://deb.debian.org|http://kartolo.sby.datautama.net.id|g' /etc/apt/sources.list 2>/dev/null || \
    echo "Using default Debian repository" || true

# Install system dependencies - Split menjadi beberapa step untuk retry yang lebih baik
# Step 1: Install essential packages dulu
RUN apt-get update --fix-missing || (sleep 10 && apt-get update --fix-missing) || (sleep 10 && apt-get update --fix-missing) \
    && apt-get install -y --no-install-recommends \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    nano \
    unzip \
    git \
    curl \
    unixodbc-dev \
    gnupg2 \
    && rm -rf /var/lib/apt/lists/*

# Step 2: Install image optimization tools (optional, bisa di-skip jika error)
RUN apt-get update --fix-missing || (sleep 10 && apt-get update --fix-missing) || true \
    && apt-get install -y --no-install-recommends \
    jpegoptim optipng pngquant gifsicle \
    || echo "Warning: Some image optimization tools failed to install" \
    && rm -rf /var/lib/apt/lists/* || true

# Step 3: Install imagemagick dan dependencies tambahan untuk PDF/Excel
RUN apt-get update --fix-missing || (sleep 10 && apt-get update --fix-missing) || true \
    && apt-get install -y --no-install-recommends \
    imagemagick \
    libmagickwand-dev \
    libicu-dev \
    libxslt1-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    libbz2-dev \
    libreadline-dev \
    libgmp-dev \
    libldap2-dev \
    libtidy-dev \
    libedit-dev \
    libsodium-dev \
    libffi-dev \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/* || true

# Step 4: Install tools untuk PDF generation (wkhtmltopdf)
RUN apt-get update --fix-missing || (sleep 10 && apt-get update --fix-missing) || true \
    && apt-get install -y --no-install-recommends \
    wkhtmltopdf \
    xvfb \
    fonts-liberation \
    fonts-dejavu-core \
    fontconfig \
    && rm -rf /var/lib/apt/lists/* || true

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    opcache \
    xml \
    zip \
    intl \
    soap \
    calendar \
    ftp \
    ldap \
    gmp \
    sockets \
    tidy \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Install Imagick extension (pastikan terinstall)
RUN pecl install imagick \
    && docker-php-ext-enable imagick

# Add Microsoft repository and install SQL Server driver
# Install prerequisites untuk GPG
RUN apt-get update --fix-missing \
    && apt-get install -y --no-install-recommends ca-certificates gnupg lsb-release \
    && rm -rf /var/lib/apt/lists/*

# Import Microsoft GPG key dengan cara yang benar untuk Debian 12
RUN curl --retry 3 --retry-delay 5 -fsSL https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor -o /usr/share/keyrings/microsoft-prod.gpg \
    && echo "deb [arch=amd64,arm64,armhf signed-by=/usr/share/keyrings/microsoft-prod.gpg] https://packages.microsoft.com/debian/12/prod bookworm main" > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update --fix-missing \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql18 mssql-tools18 \
    && echo 'export PATH="$PATH:/opt/mssql-tools18/bin"' >> ~/.bashrc \
    && rm -rf /var/lib/apt/lists/*

# Install SQL Server extensions for PHP 8.4
RUN pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js and npm (untuk build frontend React jika diperlukan)
RUN curl --retry 3 --retry-delay 5 -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get update --fix-missing \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Add user for Laravel application
RUN groupadd -g 1000 www \
    && useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory permissions
# (akan di-copy saat docker-compose up)
# Set permissions
RUN chown -R www:www /var/www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]

