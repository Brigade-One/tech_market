<?php
namespace ServerTests\Tests;

use HttpRouter;
use TechMarket\Lib\LogHandler;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../lib/http_router.php';

class MockLogHandler extends LogHandler
{
    public function logEvent($event)
    {
        // Custom logic for logging event in the mock
    }
}

class HttpRouterTest extends TestCase
{
    private HttpRouter $router;
    private MockLogHandler $mockLogHandler;

    protected function setUp(): void
    {
        $this->mockLogHandler = $this->createMock(MockLogHandler::class);
        $this->router = new HttpRouter($this->mockLogHandler);
    }
    public function testRouteValid()
    {
        $this->mockLogHandler->expects($this->never())
            ->method('logEvent');

        $this->router->addRoute('GET', '/users', function () {
            return 'User list';
        });

        $result = $this->router->route('GET', '/users');

        $this->assertEquals('User list', $result);
    }

    public function testRouteInvalid()
    {
        $this->mockLogHandler->expects($this->once())
            ->method('logEvent')
            ->with($this->equalTo('Invalid request'));

        $this->router->addRoute('GET', '/users', function () {
            return 'User list';
        });

        $result = $this->router->route('POST', '/users');

        $this->assertEquals(404, http_response_code());
        $this->expectOutputString('Invalid request');
    }
}