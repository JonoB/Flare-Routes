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

// Create a new bunch of routes for the 'books' resource
new Routely('books');

// Or use the static method
Routely::make('books');
```

This will automatically create a whole bunch of restful routes for you as follows:
```
Route::get('books/(:any)/edit', array('as' => 'book_edit', 'uses' => 'books@edit');
Route::get('books/(:any)/delete', array('as' => 'book_delete', 'uses' => 'books@delete');
Route::get('books/add', array('as' => 'book_add', 'uses' => 'books@add');
Route::get('books/(:any)', array('as' => 'book', 'uses' => 'books@item');
Route::get('books', array('as' => 'books', 'uses' => 'books@index');
Route::post('books', array('uses' => 'books@item');
Route::put('books/(:any)', array('uses' => 'books@item');
Route::delete('books/(:any)', array('uses' => 'books@item');
```

## Nested controllers
If you have nested controllers, then Routely can handle that too.
```php
Routely::make('books.comments');

Route::get('books/(:any)/comments/(:any)/edit', array('as' => 'book_comment_edit', 'uses' => 'books.comments@edit');
Route::get('books/(:any)/comments/(:any)/delete', array('as' => 'book_comment_delete', 'uses' => 'books.comments@delete');
Route::get('books/(:any)/comments/add', array('as' => 'book_comment_add', 'uses' => 'books.comments@add');
Route::get('books/(:any)/comments/(:any)', array('as' => 'book_comment', 'uses' => 'books.comments@item');
Route::get('books/(:any)/comments', array('as' => 'book_comments', 'uses' => 'books.comments@index');
Route::post('books/(:any)/comments', array('uses' => 'books.comments@item');
Route::put('books/(:any)/comments/(:any)', array('uses' => 'books.comments@item');
Route::delete('books/(:any)/comments/(:any)', array('uses' => 'books.comments@item');
```
Note that only one level of nesting is supported at this point in time

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
new Routely('books', $template);

// Will create the following routes
Route::get('books/edit/(:int)', array('as' => 'books_edit', 'uses' => 'books@edit');
Route::get('books/add', array('as' => 'books_add', 'uses' => 'books@add');
```
Note that there are two placeholders you can use when defining your templates
:name gets replaced by the route name (i.e. resource)
:singular gets replaced by the singular version of the route name (i.e. resource)