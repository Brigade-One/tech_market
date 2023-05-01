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

        $reader = new UserDataReader($this->filename);

        // Read the user data from the file
        $users = $reader->read();

        // Extract the email and password from the JSON data
        $data = json_decode($jsonData, true);
        $email = $data['email'];
        $password = $data['password'];

        // Check if there's a matching user record
        foreach ($users as $user) {
            if ($user['email'] === $email && $user['password'] === $password) {
                return ['success' => true, 'message' => 'User with name ' . $user["email"] . ' signed in successfully.']; // Authentication successful
            }
        }
        return ['success' => false, 'message' => 'No such user.']; // Authentication faile
    }

}