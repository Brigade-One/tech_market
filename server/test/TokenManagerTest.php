<?php
use PHPUnit\Framework\TestCase;
use TechMarket\Lib\TokenManager;

class TokenManagerTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/../lib/token_manager.php';
    }
    public function testGenerateToken()
    {
        $data = ['user_id' => 123];
        $tokenManager = new TokenManager();
        $token = $tokenManager->generateToken($data);

        $this->assertNotEmpty($token);
        $this->assertIsString($token);
    }

    public function testRetrieveUserData()
    {
        $data = ['data' => 'test'];
        $tokenManager = new TokenManager();
        $token = $tokenManager->generateToken($data);

        $retrievedData = TokenManager::retrieveUserData($token);

        $this->assertEquals($data['data'], $retrievedData->data);
    }

    public function testVerifyToken()
    {
        $data = ['user_id' => 123];
        $tokenManager = new TokenManager();
        $token = $tokenManager->generateToken($data);

        $isValid = TokenManager::verifyToken($token);

        $this->assertTrue($isValid);
    }

    public function testVerifyTokenWithInvalidToken()
    {
        $token = 'invalid_token';

        $isValid = TokenManager::verifyToken($token);

        $this->assertFalse($isValid);
    }
}