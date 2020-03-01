# Single file, light weight, simple PHP router

## Installation

#### Add in `.htaccess`
```
RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.+)$ index.php [QSA,L]
```
> Note: only apache server

-----

#### Require Router
```php
/**
 * Require Router
 */
require 'Router.php';
```

-----

## Use Router

- Clone this repo and `php -S localhost:4000`

-----

## Example

check [index.php](https://github.com/ronaldaug/router/blob/master/index.php)

```php
<?php

/**
 * Require Router
 */
require 'Router.php';

/**
 * Return Function
 */
$router->get('/',function(){
  echo "<h1>This is home page</h1>";
});

/**
 * Require File
 */
$router->get('/about',function(){
  require __DIR__ . '/views/about.php';
});

/**
 * With parameter
 */
$router->get('/user/:id',function($id){
  echo json_encode(["user_id"=>$id]);
});

/**
 *  Post request 
 *  The payload must be JSON format
 */
$router->post('/',function($data){
  echo json_encode(["data"=>$data]);
});

/**
 * Delete request
 */
$router->delete('/cars/:id',function($id){
  echo json_encode(["id"=>$id]);
});

/**
 * If 404 not found
 */
$router->notFound(function(){
    require __DIR__ . '/views/404.php';
});
```

> Enjoy!

