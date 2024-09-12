# Symfony & Vue.js User Management Project
## Overview
This project is a simple user management application built using Symfony for the backend and Vue.js for the frontend. It uses Docker for containerization, including services for PHP, MySQL, and Nginx.

## Getting Started
### Prerequisites
* Docker
* Docker Compose
### Setting Up the Environment
1. Change the DATABASE_URL in .env

2. Create the Required Folder Structure

Below is the folder structure to be created under the root directory:

```
Root Directory
├── php
│   └── Dockerfile
├── docker-compose.yml
└── nginx
    └── default.conf
```

## Docker Configuration
`docker-compose.yml`
`yaml`
```
version: '3.3'

services:
  database:
    container_name: database-beotest
    image: mysql:8.0
    restart: always
    command: --default-authentication-plugin=mysql_native_password
    environment:
      MYSQL_ROOT_PASSWORD: your_password
      MYSQL_DATABASE: beotest
      MYSQL_USER: your_user
      MYSQL_PASSWORD: your_password
      XDEBUG_ENABLED : 1
      XDEBUG_REMOTE_HOST : 172.17.0.1
      XDEBUG_MODE : debug
      XDEBUG_CLIENT_HOST : host.docker.internal
      XDEBUG_CLIENT_PORT : 9003
    ports:
      - '4306:3306'
    volumes:
      - ./mysql:/var/lib/mysql

  php:
    container_name: php-beotest
    build:
      context: ./php
    ports:
      - '9000:9000'
    volumes:
      - ./app:/var/www/beotest
    depends_on:
      - database

  nginx:
    container_name: nginx-beotest
    image: nginx:stable-alpine
    ports:
      - '8080:80'
    volumes:
      - ./app:/var/www/beotest
      - ./nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - php
      - database
```
`php/Dockerfile`
`dockerfile`

```
# Use the official PHP 8.0 FPM image as the base image
FROM php:8.0-fpm

# Update package list, install dependencies, PHP extensions, APCu, and configure Git
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       zlib1g-dev \
       g++ \
       git \
       libicu-dev \
       zip \
       libzip-dev \
       unzip \
    # Install PHP extensions
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    # Install and enable APCu extension via PECL
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    # Configure and install ZIP extension
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    # Clean up
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set the working directory
WORKDIR /var/www/beotest

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash - \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Configure Git with global user information
RUN git config --global user.email "YourEmailAddress@gmail.com" \
    && git config --global user.name "YourName"

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start the PHP-FPM server
CMD ["php-fpm"]
```


`nginx/default.conf`
`nginx`

```
server {
    listen 80;
    index index.php;
    server_name localhost;
    root /var/www/beotest/public;
    error_log /var/log/nginx/project_error.log;
    access_log /var/log/nginx/project_access.log;

    location / {
        try_files  $uri /index.php$is_args$args;
    }

    location ~ ^/index\\.php(/|$) {
        fastcgi_pass php:9000;
        fastcgi_split_path_info ^(.+\\.php)(/.*)$;
        include fastcgi_params;

        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;

        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
        fastcgi_busy_buffers_size 256k;

        internal;
    }

    location ~ \\.php$ {
        return 404;
    }
}
```
## Reference
This setup is based on the guide from [Webeface](https://webeface.de/how-to-set-up-a-symfony-vue-yarn-project-using-docker)

## Project Description
This project allows for the creation, updating, deletion, and viewing of users. It is built using Symfony for the backend and Vue.js for the frontend.
