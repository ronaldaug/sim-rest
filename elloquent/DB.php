<?php

define('sim-rest', TRUE);
require('Table.php');

class DB{
    public function table($table){
        return new Table($table);
    }
}
