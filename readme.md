# Flare Restful Routes Bundle

Helps you create restful routes quickly and easily

## Installation

Install via the Artisan CLI:
```sh
php artisan bundle:install routely
```

## Getting Started

Add the following to your application/bundles.php file:
```php
'routely' => array(
	'auto' => true,
)
```

You may also want to alias the namespace, so add the following to
the aliases in config/application.php:
```php
'Routely' => 'Flare\\Routely',
```

## Adding routes
Routes are created in application\routes.php as follows:

```php
// Start the bundle if its not auto-started
Bundle::start('routely');

// Create a new bunch of routes for the 'posts' resource
new Routely('posts');
```

This will automatically create a whole bunch of restful routes for you as follows:
```
Route::get('posts/(:any)/edit', array('as' => 'post_edit', 'uses' => 'posts@edit');
Route::get('posts/add', array('as' => 'post_add', 'uses' => 'posts@add');
Route::get('posts/(:any)', array('as' => 'post', 'uses' => 'posts@view');
Route::get('posts', array('as' => 'posts', 'uses' => 'posts@index');
Route::post('posts', array('uses' => 'posts@create');
Route::put('posts/(:any)', array('uses' => 'posts@update');
Route::delete('posts/(:any)', array('uses' => 'posts@destroy');
```
## Changing the template
Restful Routes uses a default template to create the above routes, but you
can easily override this template to create your own routes as follows:
```
$template = array(
    array(
        'method' => 'get',
        'route' => '/edit/(:int)',
        'as' => ':name_edit',
        'uses' => ':name@edit',
    ),
    array(
        'method' => 'get',
        'route' => '/add',
        'as' => ':name_add',
        'uses' => ':name@add',
    ),
);
new Routely('posts', $template);

// Will create the following routes
Route::get('posts/edit/(:int)', array('as' => 'posts_edit', 'uses' => 'posts@edit');
Route::get('posts/add', array('as' => 'posts_add', 'uses' => 'posts@add');
```
Note that there are two placeholders you can use when defining your templates
:name gets replaced by the route name (i.e. resource)
:singular gets replaced by the singular version of the route name (i.e. resource)