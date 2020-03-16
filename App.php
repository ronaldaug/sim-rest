<?php
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');

require 'Session.php';
require 'Helper.php';
require 'middleware/Auth.php';
require 'Router.php';
require 'elloquent/DB.php';
