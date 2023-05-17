<?php
use TechMarket\Lib\LogHandler;
use TechMarket\Lib\SignIn;
use TechMarket\Lib\SignUp;
use TechMarket\Lib\TokenManager;
use TechMarket\Lib\LineDataReader;

define("USER_FILE_PATH", "./data/users.txt");
class AuthController
{
    public static function signUp($jsonData)
    {
        $signup = new SignUp(USER_FILE_PATH, new LineDataReader(USER_FILE_PATH), new TokenManager());
        $result = $signup->processSignUpData($jsonData);
        return AuthController::sendAuthRequestResponse($result);
    }

    public static function signIn($jsonData)
    {
        $signin = new SignIn(USER_FILE_PATH);
        $result = $signin->processSignInData($jsonData);
        return AuthController::sendAuthRequestResponse($result);
    }

    public static function verifyToken($token)
    {
        require_once 'lib/token_manager.php';
        return TokenManager::verifyToken($token);
    }

    private static function sendAuthRequestResponse($result)
    {

        $logHandler = new LogHandler();

        header('Content-Type: application/json');
        if ($result['success'] === true) {
            $logHandler->logEvent($result['message']);
            return [
                'success' => true,
                'message' => $result['message'],
                'token' => $result['token'],
                'user' =>
                $result['user']
            ];
        } else {
            $logHandler->logEvent($result['message']);
            return ['success' => false, 'message' => $result['message']];
        }

    }
}