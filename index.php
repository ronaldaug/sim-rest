<?php
/**
 * Require Router
 */

require 'Helper.php';
require 'middleware/Auth.php';
require 'Router.php';
require 'elloquent/DB.php';

/**
 * Authentication 
 * payload must include username and password
 */
$router->post('/auth',function($payload){
    $auth = new Auth();
    echo $auth->login($payload);
});

/**
* Require File
*/
$router->get('/about',function(){
  require __DIR__ . '/views/about.php';
});

/**
 * Protect routes with Auth
 */
if($auth->routes()){

          /**
           * Return Function
           */
          $router->get('/',function(){
            echo "<h1>This is home page</h1>";
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
          $router->post('/', function($data){
          echo json_encode(["data"=>$data]);
          });


          /**
          * Get by Parameter
          */
          $router->get('/posts/:id',function($id){
          $post = DB::table("posts")->where("_id",$id)->get();
          echo json_encode($post);
          });

          /**
          * Get all posts
          */
          $router->get('/posts',function(){
          $posts = DB::table("posts")->all();
          echo json_encode($posts);
          });

          /**
          * Save to post
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
  return Helper::response(401,"Unauthenticated.",null);
});