# blook

A lightweight interface to list, showcase and isolate your laravel components and work on them efficiently.

## Installation

Install via composer

```
composer require raitone/blook
```

Add provider to your `config/app.php` file in `providers`

```
$providers = [
    ...
    Raitone\Blook\Providers\BlookProvider::class,
    ...
]
```

Publish the config file and adapt as needed

```
php artisan vendor:publish --provider="Raitone\Blook\Providers\BlookProvider"
```

Clean route and config

```
php artisan route:cache
php artisan config:cache
```

You should now be able to navigate on `localhost/blook` and work on your components !

## Configuration
