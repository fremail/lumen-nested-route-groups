# Lumen nested route groups

Extends a lumen application for using nested route groups.
Lumen already uses a group in bootstrap/app.php, that is why you can't use another groups in app/Http/routes.php. This lib removes the restriction.

## How to install

The current stability of this library is beta.
Be sure you have an enough stability in the composer.json file:
```
"minimum-stability": "beta",
"prefer-stable": true
```

Installing the library:

```
composer require "fremail/lumen-nested-route-groups:~1.0"
```

## How to configure
In bootstrap/app.php, change initialization of Lumen Application class to initialization of Lumen Nested Route Groups Application class.

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


### Additional namespaces configuration
By default this lib uses nested namespace ([Laravel style](https://laravel.com/docs/5.2/routing#route-group-namespaces)), but you can determine to use full namespaces instead ([Lumen style](https://lumen.laravel.com/docs/5.2/routing#route-group-namespaces)).

**Steps for using full namespaces:**

1. Create `config` directory if you don't have one in the project root.

2. Copy `NestedRouteGroups.php` from `vendor/fremail/lumen-nested-route-groups/config` folder to the created `config` dir in the root.

3. Open the `config/NestedRouteGroups.php` file and set 'namespace' value to 'full'.

4. Add this line to your bootstrap/app.php: `$app->configure('NestedRouteGroups');`


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

});
```