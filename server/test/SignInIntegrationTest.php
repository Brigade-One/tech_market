<?php
use PHPUnit\Framework\TestCase;
use TechMarket\Lib\LineDataReader;
use TechMarket\Lib\User;
use TechMarket\Lib\SignIn;

class SignInIntegrationTest extends TestCase
{
    public function testProcessSignInDataWithValidUser()
    {
        // Prepare the test data
        $jsonData = '{"name":"John","email":"john@example.com","password":"password"}';
        $filename = './../data/users.txt';

        // Create an instance of the SignIn class
        $signIn = new SignIn($filename);

        // Call the method being tested
        $result = $signIn->processSignInData($jsonData);

        // Assert the result
        $this->assertTrue($result['success']);
        $this->assertEquals('User with name John signed in successfully.', $result['message']);
        $this->assertEquals('{"name":"John","email":"john@example.com","password":"password"}', $result['user']);
        $this->assertNotEmpty($result['token']);
    }

    public function testProcessSignInDataWithInvalidUser()
    {
        // Prepare the test data
        $jsonData = '{"name":"Alice","email":"alice@example.com","password":"password"}';
        $filename = './../data/users.txt';

        // Create an instance of the SignIn class
        $signIn = new SignIn($filename);

        // Call the method being tested
        $result = $signIn->processSignInData($jsonData);

        // Assert the result
        $this->assertFalse($result['success']);
        $this->assertEquals('No such user.', $result['message']);
    }
}