# Inovação IEL

## Prerequisites
- PHP >= 5.6.24
- [Composer](https://getcomposer.org)

## Running
1. Configure database URL in `src/settings.php`
2. Install composer dependencies: `composer install`
3. Migrate database: `vendor/bin/phpmig migrate`
4. Initialize database: `docker-compose up -d`
5. Initialize PHP: `cd public ; php -S 0.0.0.0:3001`

Now it should be running on [localhost](http://0.0.0.0:3001/).
