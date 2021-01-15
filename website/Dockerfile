FROM php:apache
RUN docker-php-ext-install mysqli 

# Not needed since it is mounted as volume in docker-compose
# COPY . /var/www/html/

COPY ./startup.sh /startup.sh 
CMD chmod +x /startup.sh && /startup.sh

EXPOSE 80
