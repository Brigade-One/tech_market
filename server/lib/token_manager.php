<?php
require __DIR__ . '/../../vendor/autoload.php';

use Firebase\JWT\JWT;

class TokenManager
{
    static public $secret_key = "y@_X:MLN+l'{71F";
    static function generateToken($data)
    {
        $token = ["data" => $data];
        $jwt = JWT::encode($token, TokenManager::$secret_key);
        return $jwt;
    }
    static function retrieveUserData($token)
    {
        $decoded = JWT::decode($token, TokenManager::$secret_key, array('HS256'));
        $userJsonData = $decoded->data;
        return $userJsonData;
    }
}