<?php

namespace Dune\Tests\Unit\Routing;

use PHPUnit\Framework\TestCase;
use Dune\Tests\Bootstrap;
use Dune\Routing\RouteLoader;
use Dune\Routing\Router;

class RouteNameTest extends TestCase
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
    public function test_route_name(): void
    {
        $this->router->get('/', function () {})->name('home');
        $this->router->get('/hello/world', function () {})->name('world');
        $this->router->get('/test', function () {})->name('test');
        // test 1
        $result = \route('home');
        $expected = '/';
        $this->assertEquals($expected, $result);
        // test 2
        $result = \route('world');
        $expected = '/hello/world';
        $this->assertEquals($expected, $result);
        // test 3
        $result = \route('test');
        $expected = '/test';
        $this->assertEquals($expected, $result);
    }
    public function test_route_name_with_particles(): void
    {
        $this->router->get('/admin/{id}', function () {})->name('admin');
        $this->router->get('/admin/dashboard/{key}', function () {})->name('admin.dashboard');
        // test 1
        $result = \route('admin', ['id' => '123']);
        $expected = '/admin/123';
        $this->assertEquals($expected, $result);
        // test 2
        $result = \route('admin.dashboard', ['key' => '127']);
        $expected = '/admin/dashboard/127';
        $this->assertEquals($expected, $result);
    }
}
