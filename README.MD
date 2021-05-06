# Sim-Rest 
Sim-Rest is super lightweight tool that can serve your JSON file as a fully functional RESTFUL API. 

<img src="https://user-images.githubusercontent.com/33022876/78574189-a8534380-784f-11ea-9712-47241dbf40d7.png" width="60%">

### Features
- Only 10 KB (gzip)
- PHP 7+
- Router (GET, POST, DELETE, PUT)
- Pass parameter through router
- JSON file database
- JWT like token based authentication
- Auth protected routes
- Eloquent like DB selector
- Less configuration
- Zero dependencies

---------

## Only 3 steps to make your json file to fully functional REST API with JWT token auth.

#### 1 
Add your JSON data under `database/collections` folder. (There are two sample json dbs called `posts` and `cars`)

#### 2
Change username and password or add new users in `database/config.php` file. 

#### 3
Delare routes inside main `index.php`

Examples

**Public Route**
```php

  $router->get('/posts',function(){
      $posts = DB::table("posts")->all();
      echo json_encode($posts);
  });
  
```

**Protected Route**
```php
if($auth->routes()){

  $router->get('/posts',function(){
      $posts = DB::table("posts")->all();
      echo json_encode($posts);
  });
  
}
```

> Here, you'll see the database queries but most are very similar with Laravel eloquent.

------- 

That's it, just upload on hosting or serve via xampp/mamp and you're ready to fetch data via REST API.

Still don't get it? please check the documentation and example of todo API below.

## Documentation
[https://ronaldaug.gitbook.io/sim-rest/](https://ronaldaug.gitbook.io/sim-rest/)

## How to make a simple todo API
https://medium.com/@ronaldaug/php-restful-api-with-sim-rest-only-10-kb-422da0738e30

--------

Show your support by 🌟the project, Thanks
