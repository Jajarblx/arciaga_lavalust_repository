FROM php:8.2-apache-bookworm

# LavaLust uses PDO MySQL for the application database and mbstring in its
# request, security, and text helpers. OpenSSL, sessions, JSON, and filter are
# already provided by the official PHP image.
RUN apt-get update \
    && apt-get install -y --no-install-recommends libonig-dev \
    && docker-php-ext-install -j"$(nproc)" mbstring pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite \
    && sed -ri 's!DocumentRoot /var/www/html!DocumentRoot /var/www/html/public!g' \
        /etc/apache2/sites-available/000-default.conf

COPY docker/apache-lavalust.conf /etc/apache2/conf-available/lavalust.conf
COPY docker/php-production.ini /usr/local/etc/php/conf.d/zz-lavalust-production.ini

RUN a2enconf lavalust

WORKDIR /var/www/html
COPY . /var/www/html

# Only runtime state is writable by Apache. Application source and the public
# Aiven CA remain read-only to the web-server worker.
RUN mkdir -p runtime/cache runtime/logs runtime/session \
    && chown -R www-data:www-data runtime \
    && chmod 0750 runtime runtime/cache runtime/logs runtime/session \
    && chmod 0644 certs/ca.pem \
    && chmod 0755 docker/render-entrypoint.sh

ENTRYPOINT ["/var/www/html/docker/render-entrypoint.sh"]
CMD ["apache2-foreground"]
