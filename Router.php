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

        $this->isExit();

        if($this->method !== 'GET'){
            return;
        }

        if(isset($_GET["limit"])){
            Session::save("limit",$_GET["limit"]);
        }

        if(isset($_GET["sort"])){
            Session::save("sort",$_GET["sort"]);
        }

        if(isset($_GET["only"])){
            Session::save("only",$_GET["only"]);
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
                Session::save("isReturn",true);
                $func($param);
                break;
                default:
                break;
            }

        }else{


            // if include parameter ?limit=...

            $param_routes = null;
            if(strpos($req_routes[1],'?')){
                $param_routes = explode("?",$req_routes[1]);
            }

            $def_route = $def_routes[1];
            $req_route = $req_routes[1];
            $checkRoute = !empty($param_routes)?$param_routes[0]:$req_route;

            switch ($checkRoute) {
                case $def_route :
                Session::save("isReturn",true);
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

        $this->isExit();

        if($this->method !== 'POST'){
            return;
        }

        switch ($this->route) {
            case $route :
                Session::save("isReturn",true);
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

        $this->isExit();

        if($this->method !== 'PUT'){
            return;
        }

        switch ($this->route) {
            case $route :
                Session::save("isReturn",true);
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

        $this->isExit();

        if($this->method !== 'DELETE'){
            return;
        }

        $req_routes = explode("/",$this->route);
        $req_route = $req_routes[1];
        $param = $req_routes[2];
        $def_route = explode("/",$route)[1];

        switch ($req_route) {
            case $def_route :
                Session::save("isReturn",true);
                $func($param);
                break;
            default:
                break;
        }
    }

    /**
     * if a route is matched and retured
     */
    public function isExit(){
        if(Session::has('isReturn')){
            exit();
        }
    }

    /**
    * NOT FOUND
    * @param func
    * @return mixed
    */
    public function notFound($func){

        $this->isExit();

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