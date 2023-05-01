<?php

class SignUp
{
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function processSignUpData($jsonData)
    {
        $data = json_decode($jsonData);
        if (empty($data->name) || empty($data->email)) {
            return ['success' => false, 'message' => 'Name and email fields are required.'];
        }

        $userData = json_encode($data) . "\n";

        // Read the user data from the file
        require_once 'user_data_reader.php';
        $reader = new UserDataReader($this->filename);
        $users = $reader->read();

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