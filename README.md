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
