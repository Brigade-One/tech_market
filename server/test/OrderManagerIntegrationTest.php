<?php
use PHPUnit\Framework\TestCase;
use TechMarket\Lib\OrderManager;
use TechMarket\Lib\TokenManager;

class OrderManagerIntegrationTest extends TestCase
{
    private $filename;

    protected function setUp(): void
    {
        $this->filename = 'orders.txt';
    }
    public function testWriteOrderDataWithNewOrder()
    {
        // Prepare the test data
        $jsonData = '{"email":"john@example.com","name":"Jdohn","phoneNumber":"123456789","address":"123
Street","cardNumber":"123456789","cardCVV":"123","items":["item1","item2"]}';

        $tokenManager = new TokenManager();
        $orderManager = new OrderManager($this->filename, $tokenManager);

        $result = $orderManager->writeOrderData($jsonData);

        $this->assertTrue($result['success']);
        $this->assertEquals('Order of buyer  written successfully.', $result['message']);
        $this->assertArrayHasKey('orders', $result);
    }

    public function testWriteOrderDataWithExistingOrder()
    {
        $jsonData = '{"email":"john@example.com","name":"John","phoneNumber":"123456789","address":"123
Street","cardNumber":"123456789","cardCVV":"123","items":["item1","item2"]}';

        $tokenManager = new TokenManager();
        $orderManager = new OrderManager($this->filename, $tokenManager);

        $orderManager->writeOrderData($jsonData);
        $result = $orderManager->writeOrderData($jsonData);

        $this->assertFalse($result['success']);
        $this->assertEquals('The order has already been written.', $result['message']);
    }
}