<?php


if(!function_exists('format_result')){
    function  format_result($result){
        if(empty($result) && !isset($result)){
            return response()->json(["error" => " Not Result any data .." ],404);
        }
        if(!empty($result) && isset($result)){
            return response()->json(['data' => $result] , 200);
        }
    }
}