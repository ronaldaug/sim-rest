<?php

require('App.php');

use Auth;

/**
 * Authentication 
 * $request_data must include username and password
 */
$router->post('/auth',function($request_data){
    echo Auth::login($request_data);
});

/**
* Get the whole post collection
*/
$router->get('/posts',function(){
  $posts = DB::table("posts")->all();
  echo json_encode($posts);
});
  

/**
 * Protected routes
 */
if($auth->routes()){

          /**
          * Get request with :id parameter
          */
          $router->get('/posts/:id',function($id){
          $post = DB::table("posts")->where("_id",$id)->get();
          echo json_encode($post);
          });

          /**
          * Post request
          */
          $router->post('/posts',function($data){
          $post = DB::table("posts")->save($data);
          echo json_encode($post);
          });

          /**
          * Delete request
          */
          $router->delete('/posts/:id',function($id){
             DB::table("posts")->delete($id);
          });

          /**
          * Put request
          */
          $router->put('/posts',function($data){
          $post = DB::table("posts")->save($data);
          echo json_encode($post);
          });

}

$router->notFound(function(){
  return Helper::response(404,"Not Found!",null);
});