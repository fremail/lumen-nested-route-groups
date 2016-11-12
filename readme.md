# Changelog
**v.1.1.0** Add [$app->any() and $app->match() methods](#any-and-match-methods) (no breaking changes)

# Lumen nested route groups

Extends a lumen application for using nested route groups.
Lumen already uses a group in bootstrap/app.php, that is why you can't use another groups in app/Http/routes.php. This lib removes the restriction.

## How to install (steps)

### 1. Install using Composer

```
composer require "fremail/lumen-nested-route-groups:~1.1"
```

### 2. Required changes in bootstrap/app.php
Change initialization of Lumen Application class to initialization of Lumen Nested Route Groups Application class in bootstrap/app.php.

Before:

```
$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);
```

After:

```
$app = new Fremail\NestedRouteGroups\Application(
    realpath(__DIR__.'/../')
);
```

### After these simple steps you can use nested route groups in your application!


## Additional namespaces configuration
By default this lib uses nested namespace ([Laravel style](https://laravel.com/docs/5.2/routing#route-group-namespaces)), but you can determine to use full namespaces instead ([Lumen style](https://lumen.laravel.com/docs/5.2/routing#route-group-namespaces)).

**Steps for using full namespaces:**

1. Create `config` directory if you don't have one in the project root.

2. Copy `NestedRouteGroups.php` from `vendor/fremail/lumen-nested-route-groups/config` folder to the created `config` dir in the root.

3. Open the `config/NestedRouteGroups.php` file and set 'namespace' value to 'full'.

4. Add this line to your bootstrap/app.php: `$app->configure('NestedRouteGroups');`


## Any() and Match() methods
Do you like `any()` and `match()` methods on Laravel? I love them! That's why I added supporting them on Lumen.
The syntax is the same as for [Laravel](https://laravel.com/docs/master/routing#basic-routing):
```
$app->match($methods, $uri, $action);
```
Where 
_$methods_ - an array of methods. Example: `['get', 'post', 'delete']`. _$uri_ and _$action_ are the same as on other methods
```
$app->any($uri, $action);
```
Here are _$uri_ and _$method_ are the same as on other methods like `$app->get(...)` etc.

## Example of using this lib
This is an example of app/Http/routes.php

```
$app->group(['middleware' => 'auth'], function () use ($app) {

    $app->get('test', function () {
        echo "Hello world!";
    });

    $app->group(['prefix' => 'user'], function () use ($app) {
        $app->get('{id}', 'UserController@show');
        $app->post('/', 'UserController@store');
        $app->delete('{id}', 'UserController@destroy');
    });

    /**
     * only admins
     */
    $app->group(['middleware' => 'admin'], function () use ($app) {

        $app->group(['prefix' => 'admin'], function () use ($app) {
            $app->get('/', 'AdminController@index');
        });

    });
    
    /**
     * $app->any and $app->match available from v.1.2.0
     */
    $app->any('/', function () use ($app) {
        echo "Hey! I don't care it's POST, GET, PATCH or another method. I'll answer on any of them :)";
    });
    
    $app->match(['PATCH', 'PUT', 'DELETE'], '/old/', function () use ($app) {
        echo "This is an old part of our site without supporting REST. Please use only GET and POST here.";
    });

});
```