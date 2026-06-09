FROM yiisoftware/yii2-php:8.5-apache

# Change document root for Apache
RUN sed -i -e 's|/app/web|/app/frontend/web|g' /etc/apache2/sites-available/000-default.conf

# lil add-on

# Dynamically gen the entrypoint script including database migrations
RUN echo '#!/bin/bash\n\
set -e\n\
if [ -f "composer.json" ]; then\n\
    echo "--> Running auto Composer install 4 FRONT..."\n\
    COMPOSER_AUDIT_ARCHIVE=0 composer install --no-interaction --prefer-dist\n\
fi\n\
\n\
if [ -f "yii" ]; then\n\
    echo "--> Run auto db migrations..."\n\
    php yii migrate --interactive=0\n\
fi\n\
\n\
echo "--> Starting Apache Web Server..."\n\
exec apache2-foreground' > /usr/local/bin/docker-entrypoint.sh


# Make the generated script executable
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]