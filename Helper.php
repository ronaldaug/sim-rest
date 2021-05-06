<?php

class Helper{
    public static function response($code,$msg,$data = null){
        http_response_code($code);
        $response = [
            "status"=>$code,
            "message"=>$msg
        ];

        if(!empty($data)){
            $response["data"] = $data;
        }
        echo json_encode($response); exit();
    }
}