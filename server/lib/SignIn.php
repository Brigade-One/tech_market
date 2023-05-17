<?php
namespace TechMarket\Lib;

use TechMarket\Lib\LineDataReader;
use TechMarket\Lib\User;
use TechMarket\Lib\TokenManager;

class SignIn
{
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function processSignInData($jsonData)
    {
        $reader = new LineDataReader($this->filename);
        
        // Read the user data from the file
        $users = $reader->read();

        $receivedUser = User::fromJson($jsonData);

        // Check if there's a matching user record
        foreach ($users as $user) {
            if ($user['email'] === $receivedUser->email && $user['password'] === $receivedUser->password) {
                 
                $userObject = new User($user["name"], $user["email"], $user["password"]);
                $tokenManager = new TokenManager();
                $jwt = $tokenManager->generateToken($userObject);
                return [
                    'success' => true,
                    'message' => 'User with name ' . $userObject->name . ' signed in successfully.',
                    'user' => $userObject->toJson(),
                    'token' => $jwt
                ];
            }
        }

        return ['success' => false, 'message' => 'No such user.']; // Authentication faile
    }

}