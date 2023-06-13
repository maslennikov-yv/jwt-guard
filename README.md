# Laravel JWT Guard

## Installation

```
 composer require "maslennikov-yv/jwt-guard"
```

## Preparation
Place the following code in the boot() method of AuthServiceProvider
```
Auth::extend('jwt', function ($app, $name, array $config) use ($public_key) {
    return new JwtGuard($provider, $request, function ($token) use ($public_key) {
        try {
            $content = JWT::decode($token, new Key($public_key, 'RS256'));
            return property_exists($content, 'sub') ? $content->sub : null;
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
        return null;
    });
});
```

This example uses the Firebase JWT library which requires installation:
```
composer require firebase/php-jwt
```
But you can use any other library that allows you to validate JWT and extract useful data from it

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
