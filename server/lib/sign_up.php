<?php

class SignUp
{
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function processSignupData($jsonData)
    {


        $data = json_decode($jsonData);
        if (empty($data->name) || empty($data->email)) {
            return ['success' => false, 'message' => 'Name and email fields are required.'];
        }
        $userData = json_encode($data) . "\n";

        /* $usersData = file_get_contents($this->filename);
        $users = json_decode($usersData, true); */

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

        $email = $data->email;
        // Check if the user with the same email already exists
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                return ['success' => false, 'message' => 'User with this email already exists.'];
            }
        }

        $result = file_put_contents($this->filename, $userData, FILE_APPEND);

        if ($result !== false) {
            return ['success' => true, 'message' => 'User registered successfully.'];
        } else {
            return ['success' => false, 'message' => 'Error registering user.'];
        }
    }

}