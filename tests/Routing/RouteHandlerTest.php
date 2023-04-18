<?php

namespace Dune\Tests;

use PHPUnit\Framework\TestCase;
use Dune\Routing\RouteHandler;
use Dune\Container\Container;

class RouteHandlerTest extends TestCase
{
    protected $handler;

    protected function setUp(): void
    {
        $container = new Container();
        $this->handler = $container->get(RouteHandler::class);
    }
    public function testGetHandlerFunction(): void
    {
        $this->handler->getHandler('/test', function () { return 'Hello, World!'; });

        $routes = $this->handler->getRoutes();

        $this->assertCount(1, $routes);
        $this->assertEquals('/test', $routes[0]['route']);
        $this->assertEquals('GET', $routes[0]['method']);
        $this->assertEquals(function () { return 'Hello, World!'; }, $routes[0]['action']);
    }
    public function testPostHandlerFunction(): void
    {
        $this->handler->postHandler('/test/post', function () { return 'Hello, World!'; });

        $routes = $this->handler->getRoutes();

        $this->assertCount(2, $routes);
        $this->assertEquals('/test/post', $routes[1]['route']);
        $this->assertEquals('POST', $routes[1]['method']);
        $this->assertEquals(function () { return 'Hello, World!'; }, $routes[1]['action']);
    }
        public function testPutHandlerFunction(): void
        {
            $this->handler->putHandler('/test/put', function () { return 'Hello, World!'; });

            $routes = $this->handler->getRoutes();

            $this->assertCount(3, $routes);
            $this->assertEquals('/test/put', $routes[2]['route']);
            $this->assertEquals('PUT', $routes[2]['method']);
            $this->assertEquals(function () { return 'Hello, World!'; }, $routes[2]['action']);
        }
    public function testPatchHandlerFunction(): void
    {
        $this->handler->patchHandler('/test/patch', function () { return 'Hello, World!'; });

        $routes = $this->handler->getRoutes();

        $this->assertCount(4, $routes);
        $this->assertEquals('/test/patch', $routes[3]['route']);
        $this->assertEquals('PATCH', $routes[3]['method']);
        $this->assertEquals(function () { return 'Hello, World!'; }, $routes[3]['action']);
    }
        public function testDeleteHandlerFunction(): void
        {
            $this->handler->deleteHandler('/test/delete', function () { return 'Hello, World!'; });

            $routes = $this->handler->getRoutes();

            $this->assertCount(5, $routes);
            $this->assertEquals('/test/delete', $routes[4]['route']);
            $this->assertEquals('DELETE', $routes[4]['method']);
            $this->assertEquals(function () { return 'Hello, World!'; }, $routes[4]['action']);
        }
    public function testViewHandler(): void
    {
        $this->handler->viewHandler('/test/views', 'welcome');
        $routes = $this->handler->getRoutes();
        $this->assertCount(6, $routes);
        $this->assertEquals('/test/views', $routes[5]['route']);
        $this->assertEquals('GET', $routes[5]['method']);
        $this->assertEquals('welcome', $routes[5]['action']);
    }
    public function testSetName(): void
    {
        RouteHandler::$path = '/';
        $this->handler->setName('test.name');
        $names = $this->handler->getNames();
        $this->assertCount(1, $names);
        $this->assertEquals('/', $names['test.name']);
    }
    public function testSetMiddleware(): void
    {
        $this->handler->setMiddleware('auth');
        $middlewares = RouteHandler::$middlewares;
        $this->assertCount(1, $middlewares);
        $this->assertEquals('auth', $middlewares['/']);
    }
    public function testGetRoutes(): void
    {
        $expected = RouteHandler::$routes;
        $value = $this->handler->getRoutes();
        $this->assertEquals($expected, $value);
    }
    public function testGetPath(): void
    {
        $expected = RouteHandler::$path;
        $value = $this->handler->getPath();
        $this->assertEquals($expected, $value);
    }
    public function testGetNames(): void
    {
        $expected = RouteHandler::$names;
        $value = $this->handler->getNames();
        $this->assertEquals($expected, $value);
    }
    public function testHasMiddleware(): void
    {
        $value = $this->handler->hasMiddleware('/');
        $anotherValue = $this->handler->hasMiddleware('/test');
        $this->assertTrue($value);
        $this->assertFalse($anotherValue);
    }
    public function testGetMiddleware(): void
    {
        $value = $this->handler->getMiddleware('/');
        $expected = 'auth';
        $this->assertEquals($expected, $value);
        $value = $this->handler->getMiddleware('/nothing');
        $this->assertNull($value);
    }

}
