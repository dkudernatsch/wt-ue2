FROM richarvey/nginx-php-fpm:latest

RUN apk add --update --no-cache openldap-dev \
    && docker-php-ext-configure ldap --with-libdir=lib/ \
    && docker-php-ext-install ldap

RUN echo "TLS_REQCERT never" >> /etc/openldap/ldap.conf

# imagick
RUN apk add --update --no-cache autoconf g++ imagemagick-dev libtool make pcre-dev \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && apk del autoconf g++ libtool make pcre-dev