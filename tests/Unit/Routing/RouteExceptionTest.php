<?php

namespace Dune\Tests\Unit\Routing;

use PHPUnit\Framework\TestCase;
use Dune\Tests\Bootstrap;
use Dune\Routing\RouteLoader;
use Dune\Routing\Router;
use Dune\Routing\Exception\MethodNotSupported;
use Dune\Routing\Exception\MethodNotFound;
use Dune\Routing\Exception\RouteNotFound;
use Dune\Routing\Exception\ClassNotFound;
use Dune\Routing\Exception\MiddlewareNotFound;
use Dune\Routing\Exception\InvalidController;
use Dune\Tests\Unit\Routing\Controller\DemoController;
use Dune\Tests\Unit\Routing\Controller\TestController;

class RouteExceptionTest extends TestCase
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
    public function test_method_not_allowed_exception(): void
    {
        $this->router->post('/', function () {
            return 'hello world';
        });
        $this->expectException(MethodNotSupported::class);
        $this->router->dispatch('/', 'GET');
    }

    public function test_route_not_found_exception(): void
    {
        $this->router->post('/', function () {
            return 'hello world';
        });
        $this->expectException(RouteNotFound::class);
        $this->router->dispatch('/unknown', 'GET');
    }
    public function test_invalid_controller_exception(): void
    {
        $this->router->post('/', [DemoController::class,'test']);
        $this->expectException(InvalidController::class);
        $this->router->dispatch('/', 'POST');
    }
    public function test_method_not_found_exception(): void
    {
        $this->router->post('/', [TestController::class,'invalidMethod']);
        $this->expectException(MethodNotFound::class);
        $this->router->dispatch('/', 'POST');
    }
    public function test_class_not_found_exception(): void
    {
        $this->router->post('/', [InvalidTestController::class,'invalidMethod']);
        $this->expectException(ClassNotFound::class);
        $this->router->dispatch('/', 'POST');
    }
}
