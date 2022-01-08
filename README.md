# Laravel JWT Guard

## Installation
```
composer require maslennikov-yv/jwt-guard
```

## Preparation
Place the following code in the boot() method of AuthServiceProvider
```
Auth::extend('jwt', function ($app, $name, array $config) use ($public_key) {
    return new JwtGuard(
        $app['auth']->createUserProvider($config['provider']),
        $app['request'],
        new JwtDecoder($public_key)
    );
});
```

## Configuration
Configure 'api' guard to use jwt as driver in config/auth.php file
```
'guards' => [
    'api' => [
        'driver' => 'jwt',
        'provider' => 'users',
    ],
],
```

## Testing
```
composer test
```