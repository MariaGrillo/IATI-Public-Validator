FROM ubuntu:12.04

RUN apt-get update
RUN apt-get install -y supervisor apache2 libapache2-mod-php5 cron
RUN a2enmod php5

RUN rm /var/www/index.html
ADD . /var/www/

ADD supervisord.conf /etc/supervisor/conf.d/supervisord.conf 

EXPOSE 80

ENTRYPOINT ["/usr/bin/supervisord"]
CMD []

