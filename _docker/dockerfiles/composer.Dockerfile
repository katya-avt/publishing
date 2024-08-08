FROM composer:latest

WORKDIR /var/www/publishing

ENTRYPOINT ["composer", "--ignore-platform-reqs"]
