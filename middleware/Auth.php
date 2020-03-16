<?php

use Helper;
use Session;
define('sim-rest', TRUE);

class Auth{

    public static $admins;
    public $token;

    public function __construct(){
        $this->token = $this->getBearerToken();
    }

    /** 
     * Get header Authorization
     * */
    function getAuthorizationHeader(){
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    }
    else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        //print_r($requestHeaders);
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    return $headers;
    }

    /**
    * get access token from header
    * */
    function getBearerToken() {
    $headers = $this->getAuthorizationHeader();
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
    }
    
    public function check($token){

        self::$admins = self::getAdmin();

        $filtered = array_filter(self::$admins, function($admin) use($token){
            if($admin->token !== $token){
                return false;
            }
            return true;
        });
        
        if(empty($filtered)){
            return false;
        }

        return true;
    }

    public static function login($payload){

        self::$admins = self::getAdmin();

        $filtered = array_filter(self::$admins, function($admin) use($payload){
            if($admin->username !== $payload["username"] || $admin->password !== $payload["password"]){
                return false;
            }
            return true;
        });

        if(empty($filtered)){
            return Helper::response(401,"Invalid username or password.",null);
        }
        
        return Helper::response(200,"Token generated.",["token"=>self::generateToken($payload)]);
    }

    public function generateToken($payload){
        $token = base64_encode(bin2hex(openssl_random_pseudo_bytes(100)));
        $payload["token"] = $token;
        $updatedToken = array_map(function ($admin) use ($payload) {
            if($admin->username == $payload["username"]){
                $payload["_updated"] = time();
                return $payload;
            }
            return $admin;
        }, self::$admins);

        self::appendInConfig($updatedToken);
        return $payload["token"];
    }

    public static function getAdmin(){
        ob_start();
        require_once "database/config.php";
        $raw = ob_get_clean();
        return json_decode($raw)->admins;
    }

    /**
     * Append in database collection
     * @param array
     * @return void
     */
    public static function appendInConfig($data){

        $newData->admins = $data;

        // Append array
        $head = "<?php 
        if(!defined('sim-rest')){ exit;}         
        header('Content-Type: application/json');
?>";

        $append = $head."\n".json_encode($newData,JSON_PRETTY_PRINT);

        file_put_contents("database/config.php",$append);
    }

     /**
     * Protect Route with Auth
     */
    public function routes(){
        
        $this->isExit();

        if(empty($this->token)){
            Helper::response(400,"No token provided",null);
        }

        if(!$this->check($this->token)){
            Helper::response(401,"Unauthenticated",null);
        }
        
        return true;
    }

    public function isExit(){
        if(Session::has('isReturn')){
            exit();
        }
    }

}

$auth = new Auth();