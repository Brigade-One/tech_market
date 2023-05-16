<?php
namespace ServerTests\Tests;

use HttpRouter;
use TechMarket\Lib\LogHandler;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../lib/http_router.php';

class HttpRouterTest extends TestCase
{
    private HttpRouter $router;

    protected function setUp(): void
    {
        $this->router = new HttpRouter();
    }

    public function testRouteValid()
    {
        $this->router->addRoute('GET', '/users', function () {
            return 'User list';
        });

        $result = $this->router->route('GET', '/users');

        $this->assertEquals('User list', $result);
    }

    public function testRouteInvalid()
    {
        $this->router->addRoute('GET', '/users', function () {
            return 'User list';
        });

        $result = $this->router->route('POST', '/users');

        $this->assertEquals(404, http_response_code());
        $this->expectOutputString('Invalid request');
    }
}