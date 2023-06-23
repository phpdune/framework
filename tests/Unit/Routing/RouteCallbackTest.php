<?php

namespace Dune\Tests\Unit\Routing;

use PHPUnit\Framework\TestCase;
use Dune\Tests\Bootstrap;
use Dune\Routing\RouteLoader;
use Dune\Routing\Router;
use Dune\Tests\Unit\Routing\Controller\TestController;

class RouteCallbackTest extends TestCase
{
    public $router = null;
    public $bootstraped = false;

    public function setUp(): void
    {
        if(!$this->bootstraped) {
            $bootstrap = (new Bootstrap())->run();
            $this->bootstraped = true;
        }
        if(!$this->router) {
            $this->router = (new RouteLoader())->load();
        }
    }
    public function tearDown(): void
    {
        $this->router->clear();
    }
    public function test_route_with_closure()
    {
        $this->router->get('/', function () {
            return 'test';
        });
        $result = $this->router->dispatch('/', 'GET');
        $expected = 'test';
        $this->assertEquals($expected, $result);
    }
    public function test_route_with_controller()
    {
        $this->router->get('/', [TestController::class,'test']);
        $this->router->get('/show', [TestController::class,'show']);
        // test 1
        $result = $this->router->dispatch('/', 'GET');
        $expected = 'controller test';
        $this->assertEquals($expected, $result);
        //test 2
        $result = $this->router->dispatch('/show', 'GET');
        $expected = 'dune test';
        $this->assertEquals($expected, $result);
    }
}
