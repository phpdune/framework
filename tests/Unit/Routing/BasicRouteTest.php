<?php

namespace Dune\Tests\Unit\Routing;

use PHPUnit\Framework\TestCase;
use Dune\Tests\Bootstrap;
use Dune\Routing\RouteLoader;
use Dune\Routing\Router;

class BasicRouteTest extends TestCase
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

    public function test_get_route(): void
    {
        $this->router->get('/', function () {
            return 'hello world';
        });
        $this->router->get('/test', function () {
            return 'testing';
        });
        // test 1
        $result = $this->router->dispatch('/', 'GET');
        $expected = 'hello world';
        $this->assertEquals($expected, $result);
        //test 2
        $result = $this->router->dispatch('/test', 'GET');
        $expected = 'testing';
        $this->assertEquals($expected, $result);
    }

    public function test_post_route(): void
    {
        $this->router->post('/', function () {
            return 'hello world';
        });
        $this->router->post('/test', function () {
            return 'testing';
        });
        // test 1
        $result = $this->router->dispatch('/', 'POST');
        $expected = 'hello world';
        $this->assertEquals($expected, $result);
        //test 2
        $result = $this->router->dispatch('/test', 'POST');
        $expected = 'testing';
        $this->assertEquals($expected, $result);
    }

    public function test_put_route(): void
    {
        $this->router->put('/', function () {
            return 'hello world';
        });
        $this->router->put('/test', function () {
            return 'testing';
        });
        // test 1
        $result = $this->router->dispatch('/', 'PUT');
        $expected = 'hello world';
        $this->assertEquals($expected, $result);
        //test 2
        $result = $this->router->dispatch('/test', 'PUT');
        $expected = 'testing';
        $this->assertEquals($expected, $result);
    }

    public function test_patch_route(): void
    {
        $this->router->patch('/', function () {
            return 'hello world';
        });
        $this->router->patch('/test', function () {
            return 'testing';
        });
        // test 1
        $result = $this->router->dispatch('/', 'PATCH');
        $expected = 'hello world';
        $this->assertEquals($expected, $result);
        //test 2
        $result = $this->router->dispatch('/test', 'PATCH');
        $expected = 'testing';
        $this->assertEquals($expected, $result);
    }

    public function test_delete_route(): void
    {
        $this->router->delete('/', function () {
            return 'hello world';
        });
        $this->router->delete('/test', function () {
            return 'testing';
        });
        // test 1
        $result = $this->router->dispatch('/', 'DELETE');
        $expected = 'hello world';
        $this->assertEquals($expected, $result);
        //test 2
        $result = $this->router->dispatch('/test', 'DELETE');
        $expected = 'testing';
        $this->assertEquals($expected, $result);
    }

}
