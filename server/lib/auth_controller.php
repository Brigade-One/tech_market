<?php

define("FILE_PATH", "./data/users.txt");
class AuthController
{
    public static function signUp($jsonData)
    {
        require_once 'lib/sign_up.php';
        $signup = new SignUp(FILE_PATH);
        $result = $signup->processSignUpData($jsonData);
        AuthController::sendAuthRequestResponse($result);
    }

    public static function signIn($jsonData)
    {
        require_once 'lib/sign_in.php';
        $signin = new SignIn(FILE_PATH);
        $result = $signin->processSignInData($jsonData);
        return AuthController::sendAuthRequestResponse($result);
    }

    public static function verifyToken($token)
    {
        require_once 'lib/token_manager.php';
        $tokenManager = new TokenManager();
        return $tokenManager->verifyToken($token);
    }

    private static function sendAuthRequestResponse($result)
    {
        require_once 'lib/log_handler.php';
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
            var_dump($result['user']);
        } else {
            $logHandler->logEvent($result['message']);
            return ['success' => false, 'message' => $result['message']];
        }

    }
}