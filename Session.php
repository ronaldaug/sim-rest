<?php

class Session{

    public function __construct(){
        session_start();
    }

    public static function all(){
        return $_SESSION;
    }

    public static function save($key,$val){
        $_SESSION[$key] = $val;
    }

    public static function get($key){
       return isset($_SESSION[$key])?$_SESSION[$key]:'';
    }

    public static function forget($key){
        unset($_SESSION[$key]);
    }

    public static function has($key){
        return isset($_SESSION[$key]);
    }

}