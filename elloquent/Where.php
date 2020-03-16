<?php

class Where{

    public $key;
    public $val;
    public $collect;
    public $filtered = [];
    public function __construct($collect,$key,$val){
        $this->collect = $collect;
        $this->key = $key;
        $this->val = $val;
        
        foreach($collect as $item){
            if($item->$key == $val){
                array_push($this->filtered,$item);
            }
        }
    }

    public function get(){
        return $this->filtered;
    }

}