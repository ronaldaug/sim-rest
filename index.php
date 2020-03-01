<?php
/**
 * Require Router
 */
require 'Router.php';
require 'elloquent/DB.php';

/**
 * Return Function
 */
$router->get('/',function(){
  echo "<h1>This is home page</h1>";
});

$router->get('/posts/:id',function($id){
  $post = DB::table("posts")->where("_id",$id)->get();
  echo json_encode($post);
});

$router->get('/posts',function(){
  $posts = DB::table("posts")->get();
  echo json_encode($posts);
});

$router->post('/posts',function($data){
  $post = DB::table("posts")->save($data);
  echo json_encode($post);
});

$router->put('/posts',function($data){
  $post = DB::table("posts")->save($data);
  echo json_encode($post);
});

$router->get('/specific',function(){
  echo DB::table("cars")->where("description","haha")->get();
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