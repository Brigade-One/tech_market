<?php
class SignIn
{
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function processSignInData($jsonData)
    {
        require_once 'user_data_reader.php';
        require_once 'user.php';
        $reader = new UserDataReader($this->filename);

        // Read the user data from the file
        $users = $reader->read();

        $receivedUser = User::fromJson($jsonData);

        // Check if there's a matching user record
        foreach ($users as $user) {
            if ($user['email'] === $receivedUser->email && $user['password'] === $receivedUser->password) {
                $userObject = new User($user["name"], $user["email"], $user["password"]);
                return [
                    'success' => true,
                    'message' => 'User with name ' . $userObject->name . ' signed in successfully.',
                    'user' => $userObject->toJson()
                ];
            }
        }

        return ['success' => false, 'message' => 'No such user.']; // Authentication faile
    }

}