# Use a imagem oficial do PHP com Apache
FROM php:8.1-apache

# Habilite módulos do Apache e PHP necessários
RUN a2enmod rewrite
RUN docker-php-ext-install pdo pdo_mysql

# Instale o Composer para gerenciar dependências do Laravel
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Defina o diretório de trabalho no contêiner
WORKDIR /var/www

# Copie os arquivos do seu projeto Laravel para o contêiner
COPY . .

# Instale as dependências do Laravel
RUN composer install

# Configure as permissões para o diretório de armazenamento do Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expõe a porta 80 para acesso HTTP
EXPOSE 80
