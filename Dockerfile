FROM debian:latest

# Instalar Apache2
RUN apt-get update && \
    apt-get install -y apache2 && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Copiar index.html al DocumentRoot
COPY index.html /var/www/html/index.html

# Modificar puerto a 8000
RUN sed -i 's/Listen 80/Listen 8000/' /etc/apache2/ports.conf && \
    sed -i 's/<VirtualHost \*:80>/<VirtualHost *:8000>/' /etc/apache2/sites-available/000-default.conf

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Exponer puerto 8000
EXPOSE 8000

# Iniciar Apache en foreground
CMD ["apache2ctl", "-D", "FOREGROUND"]
