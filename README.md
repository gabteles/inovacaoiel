# Inovação IEL

## Prerequisites
- PHP >= 5.6.24
- [Composer](https://getcomposer.org)
- [Docker](https://www.docker.com/)

## Running
1. Configure database URL in `src/settings.php` (if you run database using step 4, check next title to get it)
2. Install composer dependencies: `composer install`
3. Migrate database: `vendor/bin/phpmig migrate`
4. Initialize database: `docker-compose up -d`
5. Initialize PHP: `cd public ; php -S 0.0.0.0:3001`

## Services
If you setup everything right, you should end with:
- PHP running on http://0.0.0.0:3001
- MySQL running on `mysql://root:12345678@127.0.0.1:3306/inovacaoiel?reconnect=true`
- PHPMyAdmin running on localhost:8080
