<?php

class Where{

    public $key;
    public $val;
    public $collect;
    public $filtered;
    public function __construct($collect,$key,$val){
        $this->collect = $collect;
        $this->key = $key;
        $this->val = $val;
        $this->filtered = array_filter($collect, function ($item) use ($key,$val){
            if (stripos($item->$key, $val) !== false) {
                return true;
            }
            return false;
        });
    }

    public function get(){
        return $this->filtered;
    }

}