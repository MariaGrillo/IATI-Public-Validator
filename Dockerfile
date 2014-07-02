FROM ubuntu:12.04

RUN apt-get update
RUN apt-get install -y supervisor apache2 libapache2-mod-php5 cron git-core
RUN a2enmod php5
RUN rm /var/www/index.html

VOLUME ["/var/log/apache2", "/var/www/upload", "/validatorlog"]

ADD supervisord.conf /etc/supervisor/conf.d/supervisord.conf 

ADD . /var/www/
RUN cp /var/www/docker.settings.php /var/www/settings.php
RUN cd /var/www/ && ./get_iati_schemas.sh
RUN chmod a+w /validatorlog

EXPOSE 80

ENTRYPOINT ["/usr/bin/supervisord"]
CMD []

