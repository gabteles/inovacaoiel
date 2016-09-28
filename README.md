# Inovação IEL

## Prerequisites
- PHP >= 5.6.24
- [Composer](https://getcomposer.org)
- [Docker](https://www.docker.com/)

## Running
1. Configure database URL in `src/settings.php` (if you run database using step 4, check next title to get credentials)
2. Install composer dependencies: `composer install`
3. Migrate database: `vendor/bin/phpmig migrate`
4. Initialize database: `docker-compose up -d`
5. Initialize PHP: `cd public ; php -S 0.0.0.0:3001`

## Services
If you setup everything right, you should end with:
- PHP running on http://0.0.0.0:3001
- MySQL running on localhost:3306, with user **root** and password **12345678**.
- PHPMyAdmin running on localhost:8080
