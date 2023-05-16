<?php
use  TechMarket\Lib\LineDataReader;
use TechMarket\Lib\User;
class SignUp
{
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
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
        $reader = new LineDataReader($this->filename);
        $users = $reader->read();


        // Check if the user with the same email already exists
        foreach ($users as $user) {
            if ($user['email'] === $receivedUser->email) {
                return ['success' => false, 'message' => 'User with ' . $user['email'] . ' email already exists.'];
            }
        }

        $result = file_put_contents($this->filename, $userData, FILE_APPEND);

        if ($result !== false) {
            require_once 'lib/token_manager.php';

            $jwt = TokenManager::generateToken($receivedUser);
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