<?php
namespace ServerTests\Tests;

use HttpRouter;
use TechMarket\Lib\LogHandler;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

require_once __DIR__ . '/../lib/http_router.php';

class HttpRouterTest extends TestCase
{
    private HttpRouter $router;

    /**
     *  @var MockObject|LogHandler
     */
    private MockObject $logHandler;

    protected function setUp(): void
    {
        $this->logHandler = $this->createMock(LogHandler::class);
        $this->router = new HttpRouter($this->logHandler);
    }
    public function testRouteValid()
    {
        $this->logHandler->expects($this->once())
            ->method('logEvent');

        $this->router->addRoute('GET', '/users', function () {
            return 'User list';
        });

        $result = $this->router->route('GET', '/users');

        $this->assertEquals('User list', $result);
    }

    public function testRouteInvalid()
    {
        $this->logHandler->expects($this->once())
            ->method('logEvent')
            ->with($this->equalTo('Invalid request to /users'));

        $this->router->addRoute('GET', '/users', function () {
            return 'User list';
        });

        $result = $this->router->route('POST', '/users');

        $this->assertEquals(404, http_response_code());
        $this->expectOutputString('Invalid request');
    }
}