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
        // Read the user data from the file
        $users = [];
        $handle = fopen($this->filename, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $user = json_decode($line, true);
                $users[] = $user;
            }
            fclose($handle);
        } else {
            return ['success' => false, 'message' => 'Error reading user data.'];
        }

        // Extract the email and password from the JSON data
        $data = json_decode($jsonData, true);
        $email = $data['email'];
        $password = $data['password'];

        // Check if there's a matching user record
        foreach ($users as $user) {
            if ($user['email'] === $email && $user['password'] === $password) {
                return ['success' => true, 'message' => 'User with name ' . $user["name"] . ' signed in successfully.']; // Authentication successful
            }
        }
        return ['success' => false, 'message' => 'No such user.']; // Authentication faile
    }
}