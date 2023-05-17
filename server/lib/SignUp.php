<?php
namespace TechMarket\Lib;

use Exception;

class SignUp
{
    private $filename;
    private $lineDataReader;
    private $tokenManager;

    public function __construct($filename, LineDataReader $lineDataReader, TokenManager $tokenManager)
    {
        $this->filename = $filename;
        $this->lineDataReader = $lineDataReader;
        $this->tokenManager = $tokenManager;
    }

    public function processSignUpData($jsonData)
    {
        require_once 'user.php';

        $receivedUser = User::fromJson($jsonData);

        if (empty($receivedUser->name) || empty($receivedUser->email)) {
            return ['success' => false, 'message' => 'Name and email fields are required.'];
        }

        $userData = $receivedUser->toJson() . "\n";

        // Read the user data from the file
        $users = $this->lineDataReader->read();

        // Check if the user with the same email already exists
        foreach ($users as $user) {
            if ($user['email'] === $receivedUser->email) {
                return ['success' => false, 'message' => 'User with ' . $user['email'] . ' email already exists.'];
            }
        }

        $result = file_put_contents($this->filename, $userData, FILE_APPEND);

        if ($result !== false) {
            $jwt = $this->tokenManager->generateToken($receivedUser);
            return [
                'success' => true,
                'message' => 'User with name ' . $receivedUser->name . ' signed up successfully.',
                'user' => $receivedUser->toJson(),
                'token' => $jwt
            ];
        } else {
            return ['success' => false, 'message' => 'Signing up error.'];
        }
    }
}