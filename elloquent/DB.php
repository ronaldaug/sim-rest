<?php

require('Table.php');

class DB{
    public static function table($table){
        return new Table($table);
    }
}
