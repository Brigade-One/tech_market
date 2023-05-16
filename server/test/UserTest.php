<?php

use PHPUnit\Framework\TestCase;
use TechMarket\Lib\User;

class UserTest extends TestCase
{
    public function testUserCanBeConstructedAndPropertiesSet()
    {
        $user = new User("John Doe", "johndoe@example.com", "password123");
        $this->assertEquals("John Doe", $user->name);
        $this->assertEquals("johndoe@example.com", $user->email);
        $this->assertEquals("password123", $user->password);
    }

    public function testUserCanBeSerializedToJsonAndDeserialized()
    {
        $user = new User("John Doe", "johndoe@example.com", "password123");
        $json = $user->toJson();
        $this->assertEquals('{"name":"John Doe","email":"johndoe@example.com","password":"password123"}', $json);
        $deserializedUser = User::fromJson($json);
        $this->assertEquals($user, $deserializedUser);
    }
}