<?php
class User
{
    public $name;
    public $email;
    public $password;

    public function __construct($name, $email, $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function toJson()
    {
        return json_encode(['name' => $this->name, 'email' => $this->email, 'password' => $this->password]);
    }
    public static function fromJson($json)
    {
        $data = json_decode($json, true);
        return new User($data['name'], $data['email'], $data['password']);
    }
}