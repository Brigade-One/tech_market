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
        require_once 'user_data_reader.php';

        $data = json_decode($jsonData);
        if (empty($data->name) || empty($data->email)) {
            $logHandler->logEvent("Failed  ' . $data->email . 'signed up try");
            return ['success' => false, 'message' => 'Name and email fields are required.'];
        }

        $userData = json_encode($data) . "\n";

        // Read the user data from the file
        $reader = new UserDataReader($this->filename);
        $users = $reader->read();


        // Check if the user with the same email already exists
        $email = $data->email;
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                return ['success' => false, 'message' => 'User with ' . $user['email'] . ' email already exists.'];
            }
        }

        $result = file_put_contents($this->filename, $userData, FILE_APPEND);

        if ($result !== false) {
            return ['success' => true, 'message' => 'User' . $user['email'] . ' signed up successfully.'];
        } else {
            return ['success' => false, 'message' => 'Signing up error.'];
        }
    }

}