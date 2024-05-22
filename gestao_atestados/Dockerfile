# Use a imagem oficial do PHP 8.1 com Apache
FROM php:8.1-apache

# Atualize o sistema e instale as dependências necessárias
RUN apt-get update && \ 
    apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Instale o Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instale Node.js e npm
RUN curl -fsSL https://deb.nodesource.com/setup_14.x | bash - && \
    apt-get install -y nodejs

# Habilitar o mod_rewrite do Apache
RUN a2enmod rewrite

# Copie o código-fonte do Laravel para o diretório /var/www/html
COPY . /var/www/html/atestadosManager

# Configure as permissões do Laravel
RUN chown -R www-data:www-data /var/www/html/atestadosManager/storage /var/www/html/atestadosManager/bootstrap/cache

# Exponha a porta 80 do Apache
EXPOSE 80

# Comando de inicialização do Apache
CMD ["apache2-foreground"]

# Execute o Composer para instalar as dependências do Laravel
WORKDIR /var/www/html/atestadosManager