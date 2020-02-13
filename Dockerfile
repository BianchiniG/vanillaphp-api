FROM gliderlabs/alpine
MAINTAINER German Bianchini - https://github.com/bianchinig - https://gitlab.com/germanb - https://hub.docker.com/u/germanb

RUN apk --update add apache2 php-apache2 php-mysqli php-pdo php-cli && \
    rm -f /var/cache/apk/* && \
    mkdir /app && mkdir /app/public && \
    chown -R apache:apache /app && \
    sed -i 's#^DocumentRoot ".*#DocumentRoot "/app/public"#g' /etc/apache2/httpd.conf && \
    sed -i 's#AllowOverride none#AllowOverride All#' /etc/apache2/httpd.conf && \
    sed -i '/LoadModule rewrite_module/s/^#//g' /etc/apache2/httpd.conf && \
    sed -i '/<Directory "\/var\/www\/localhost\/htdocs">/s/\/var\/www\/localhost\/htdocs/\/app\/public/g' /etc/apache2/httpd.conf

ADD entry.sh /scripts/entry.sh
RUN chmod -R 755 /scripts

EXPOSE 80

WORKDIR /app

ENTRYPOINT ["/scripts/entry.sh"]