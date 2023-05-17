<?php
use PHPUnit\Framework\TestCase;
use TechMarket\Lib\DBController;
use TechMarket\Lib\ConnectDB;

class DBControllerIntegrationTest extends TestCase
{
    private $dbController;


    protected function setUp(): void
    {
        $connectDB = new ConnectDB('localhost', 'techmarket', 'root', '');
        $this->dbController = new DBController($connectDB);
    }

    public function testGetItemsByQuery()
    {
        $query = "SELECT * FROM items";

        $result = $this->dbController->getItemsByQuery($query);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('id', $result[0]);
        $this->assertArrayHasKey('name', $result[0]);
        $this->assertArrayHasKey('price', $result[0]);
        $this->assertArrayHasKey('FID_Vendor', $result[0]);
        $this->assertArrayHasKey('FID_Category', $result[0]);
    }

    public function testGetItemInstanceByID()
    {
        $id = 1;

        $result = $this->dbController->getItemInstanceByID($id);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('price', $result);
        $this->assertArrayHasKey('FID_Vendor', $result);
        $this->assertArrayHasKey('FID_Category', $result);
    }
}