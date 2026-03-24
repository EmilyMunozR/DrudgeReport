# Usamos PHP con Apache
FROM php:8.2-apache

# Copiamos todo el proyecto al directorio web del contenedor
COPY . /var/www/html/

# Habilitamos extensiones comunes (ejemplo: mysqli para MySQL)
RUN docker-php-ext-install mysqli

# Exponemos el puerto 80 para que Render lo sirva
EXPOSE 80
