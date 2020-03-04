<?php

class Router{
    
    protected $route;
    protected $method;
    protected $data;
    protected $routes = [];
    public function __construct($req){
        $this->route = $req['route'];
        $this->method = $req['method'];
        $this->data = $req['data'];
    }

    /**
     * GET request
     * @param route 
     * @param func
     * @return mixed
     */
    public function get($route,$func){

        if($this->method !== 'GET'){
            return;
        }

        $def_routes = explode("/", $route);

        $req_routes = explode("/",$this->route);

        array_push($this->routes,$def_routes[1]);

        if(count($def_routes) !== count($req_routes)){
            return;
        }

        // route with parameter
        if(count($req_routes) > 2){
            $req_route = $req_routes[1];
            $def_route = $def_routes[1];
            $param = $req_routes[2];

            switch ($req_route) {
                case $def_route :
                $func($param);
                break;
                default:
                break;
            }

        }else{

            switch ($this->route) {
                case $route :
                    $func();
                    break;
                default:
                    break;
            }
            
        }

    }

    /**
    * POST request
    * @param route 
    * @param func
    * @return mixed
    */
    public function post($route,$func){

        if($this->method !== 'POST'){
            return;
        }
        
        switch ($this->route) {
            case $route :
                $func($this->data);
                break;
            default:
                break;
        }
    }

    /**
    * POST request
    * @param route 
    * @param func
    * @return mixed
    */
    public function put($route,$func){

        if($this->method !== 'PUT'){
            return;
        }
        
        switch ($this->route) {
            case $route :
                $func($this->data);
                break;
            default:
                break;
        }
    }


    /**
    * Delete request
    * @param route 
    * @param func
    * @return mixed
    */
    public function delete($route,$func){

        if($this->method !== 'DELETE'){
            return;
        }

        $req_routes = explode("/",$this->route);
        $req_route = $req_routes[1];
        $param = $req_routes[2];
        $def_route = explode("/",$route)[1];

        switch ($req_route) {
            case $def_route :
                $func($param);
                break;
            default:
                break;
        }
    }

    /**
    * NOT FOUND
    * @param func
    * @return mixed
    */
    public function notFound($func){
        if($this->method !== "GET"){
            return;
        }
        if(in_array(explode("/",$this->route)[1],$this->routes) == false){
            http_response_code(404);
            $func();
        }
    }
}

/** 
 * Route Request and Methods
 */
$req = ["method" => $_SERVER['REQUEST_METHOD'],"route" => $_SERVER['REQUEST_URI'], "data"=>json_decode(file_get_contents('php://input'), true)];

/**
 * Init
 */
$router = new Router($req);