<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Steps
## Prepare Server
- Install <a href="https://techvblogs.com/blog/install-nginx-on-ubuntu-20-04">Nginx</a>
- Install composer `sudo apt-get install git composer -y`
- Install Php 7.4 if not exist
- Install php-fpm `sudo apt-get install php-fpm php-mysql`
- Install <a href="https://www.digitalocean.com/community/tutorials/how-to-install-and-use-composer-on-ubuntu-20-04"> Composer</a>
- install MYSQL 
    - `sudo apt install mysql-server`
    - `sudo systemctl start mysql.service`
- create mysql user, databases and linux user

## Github Actions
- navigate under .github/workflows to see existing workflow that fires on Pull request and push 
- store .env files under settings/secrets
- store ssh_private key under settings/secrets to automate deployment
- Get server list of known devices `ssh-keyscan rsa -t {server_ip_address}` and store the value containing  **{ip address} ssh-rsa** in settings/secrets

## Setup Deployer
- Using deployer `composer require deployer/deployer deployer/recipes` locally in the project 
- see the ideal deploy.php file after setup

# Sample nginx config 
```
server {
    listen 80;
    server_name ip/domain;

    root /var/www/todolist_app/current/public;
    index index.php index.html index.htm;

    error_log  /var/log/nginx/todolist.error_log  warn;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    location /api {
        root /var/www/todolist_app/current/routes;
        
        # Rewrite $uri=/api/v1/xyz back to just $uri=/xyz
        rewrite ^/api/v1/(.*)$ /$1 break;

        # Try to send static file at $url or $uri/
        # Else try /index.php (which will hit location ~\.php$ below)
        try_files $uri $uri/ /index.php?$args;
    }

    # Handle all locations *.php files (which will always be just /index.php)
    # via factcgi PHP-FPM unix socket
    location ~ \.php$ {
        # At this piont, $uri is /index.php, $args=any GET ?key=value
        # and $request_uri = /api/v1/xyz.  But we DONT want to 
        # /api/v1/xyz to PHP-FPM, we want just /xyz to pass to
        # fastcgi REQUESTE_URI below. This allows laravel to see
        # /api/v1/xyz as just /xyz in its router.  So laravel route('/xyz') responds
        # to /api/v1/xyz as you would expect.
        set $newurl $request_uri;
        if ($newurl ~ ^/api/v1(.*)$) {
                set $newurl $1;
                root /var/www/todolist_app/current/routes;
        }
        # Pass all PHP files to fastcgi php fpm unix socket
        fastcgi_split_path_info ^(.+\.php)(/.+)$;

        fastcgi_pass unix:/run/php/php-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param REQUEST_URI $newurl;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    location ~ /\.ht {
        deny all;
    }
}
```