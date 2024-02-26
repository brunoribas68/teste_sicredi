FROM php:apache
RUN a2enmod rewrite
RUN service apache2 restart
ENV TOKEN_CERTISIGN ${TOKEN_CERTISIGN}
ENV URL_CERTISIGN ${URL_CERTISIGN}
ENV HOST ${HOST}
RUN echo 'SetEnv TOKEN_CERTISIGN ${TOKEN_CERTISIGN}' > /etc/apache2/conf-enabled/environment.conf
RUN echo 'SetEnv URL_CERTISIGN ${URL_CERTISIGN}' > /etc/apache2/conf-enabled/environment.conf
RUN echo 'SetEnv HOST ${HOST}' > /etc/apache2/conf-enabled/environment.conf
