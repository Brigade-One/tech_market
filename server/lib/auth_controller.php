<?php
class AuthController
{
    public static function signUp($filename)
    {
        require_once 'lib/sign_up.php';
        $signup = new SignUp($filename);
        $jsonData = file_get_contents('php://input');
        $result = $signup->processSignUpData($jsonData);
        AuthController::sendAuthRequestResponse($result);
    }

    public static function signIn($filename)
    {
        require_once 'lib/sign_in.php';
        $logHandler = new LogHandler();
        $signin = new SignIn($filename);
        $jsonData = file_get_contents('php://input');
        $result = $signin->processSignInData($jsonData);
        AuthController::sendAuthRequestResponse($result);
        $logHandler->logEvent($result['message']);
    }

    public static function verifyToken($token)
    {
        $tokenManager = new TokenManager();
        return $tokenManager->verifyToken($token);
    }

    private static function sendAuthRequestResponse($result)
    {
        require_once 'lib/log_handler.php';
        $logHandler = new LogHandler();

        header('Content-Type: application/json');
        if ($result['success'] === true) {
            echo json_encode([
                'success' => true,
                'message' => $result['message'],
                'token' => $result['token'],
                'user' =>
                $result['user']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => $result['message']]);
        }
        $logHandler->logEvent($result['message']);
    }
}