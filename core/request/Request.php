<?php

namespace request;

class Request
{
    public static function getRequestParam($paramName){
        $paramValue = $_GET[$paramName] ?? null;
        if (!$paramValue) {
            $paramValue = $_POST[$paramName] ?? null;
        }

        return $paramValue;
    }
}