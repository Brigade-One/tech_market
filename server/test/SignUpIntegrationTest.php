<?php
use PHPUnit\Framework\TestCase;
use TechMarket\Lib\SignUp;

class SignUpIntegrationTest extends TestCase
{
    public function testProcessSignUpDataWithValidData()
    {
        // Prepare the test data
        $jsonData = '{"name":"John","email":"john@example.com","password":"password"}';
        $filename = 'users.txt';

        // Create a mock for LineDataReader
        $lineDataReaderMock = $this->getMockBuilder(\TechMarket\Lib\LineDataReader::class)
            ->disableOriginalConstructor()
            ->getMock();
        $lineDataReaderMock->method('read')->willReturn([]);

        // Create a mock for TokenManager
        $tokenManagerMock = $this->getMockBuilder(\TechMarket\Lib\TokenManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $tokenManagerMock->method('generateToken')->willReturn('mockedToken');

        // Create an instance of the SignUp class with the mocks
        $signUp = new SignUp($filename, $lineDataReaderMock, $tokenManagerMock);

        // Call the method being tested
        $result = $signUp->processSignUpData($jsonData);

        // Assert the result
        $this->assertTrue($result['success']);
        $this->assertEquals('User with name John signed up successfully.', $result['message']);
        $this->assertEquals('{"name":"John","email":"john@example.com","password":"password"}', $result['user']);
        $this->assertEquals('mockedToken', $result['token']);
    }

    public function testProcessSignUpDataWithExistingEmail()
    {
        // Prepare the test data
        $jsonData = '{"name":"John","email":"john@example.com","password":"password"}';
        $filename = 'users.txt';

        $expectedData = [
            [
                'name' => 'John',
                'email' => 'john@example.com',
                'password' => 'password'
            ]
        ];

        // Create a mock for LineDataReader
        $lineDataReaderMock = $this->getMockBuilder(\TechMarket\Lib\LineDataReader::class)
            ->disableOriginalConstructor()
            ->getMock();
        $lineDataReaderMock->method('read')->willReturn($expectedData);

        // Create a mock for TokenManager
        $tokenManagerMock = $this->getMockBuilder(\TechMarket\Lib\TokenManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $tokenManagerMock->method('generateToken')->willReturn('mockedToken');

        // Create an instance of the SignUp class
        $signUp = new SignUp($filename, $lineDataReaderMock, $tokenManagerMock);

        // Call the method being tested
        $result = $signUp->processSignUpData($jsonData);

        // Assert the result
        $this->assertFalse($result['success']);
        $this->assertEquals('User with john@example.com email already exists.', $result['message']);
    }

}