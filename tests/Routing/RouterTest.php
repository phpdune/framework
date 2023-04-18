<?php

namespace Dune\Tests;

use PHPUnit\Framework\TestCase;
use Dune\Routing\Router as Route;
use Dune\Routing\Exception\RouteNotFound;
use Dune\Routing\Exception\MethodNotSupported;

class RouterTest extends TestCase
{
    public function testGetRoute(): void
    {
        $value = Route::get('/', function () {
            return 'hello world';
        });
        $this->assertInstanceOf(Route::class, $value);
    }

    public function testPostRoute(): void
    {
        $value = Route::post('/post', function () {
            return 'hello world';
        });
        $this->assertInstanceOf(Route::class, $value);
    }
    public function testPutRoute(): void
    {
        $value = Route::put('/put', function () {
            return 'hello world';
        });
        $this->assertInstanceOf(Route::class, $value);
    }
    public function testPatchRoute(): void
    {
        $value = Route::patch('/patch', function () {
            return 'hello world';
        });
        $this->assertInstanceOf(Route::class, $value);
    }
    public function testDeleteRoute(): void
    {
        $value = Route::delete('/patch', function () {
            return 'hello world';
        });
        $this->assertInstanceOf(Route::class, $value);
    }
    public function testViewRoute(): void
    {
        $value = Route::view('/welcome', 'test');
        $this->assertInstanceOf(Route::class, $value);
    }
    public function testRouteName(): void
    {
        $value = Route::get('/test123', function () {
            return 'welcome';
        })->name('test.name');
        $this->assertInstanceOf(Route::class, $value);
    }
    public function testRouteMiddleware(): void
    {
        $value = Route::get('/d-test', function () {
            return 'hello';
        })->middleware('auth');
        $this->assertInstanceOf(Route::class, $value);
    }
    public function testRouteNotFoundException(): void
    {
        $this->expectException(RouteNotFound::class);
        Route::run('/not-found', 'GET');
    }
    public function testRouteMethodNotSupportedException(): void
    {
        Route::get('/testMethod', function () {
            //
        });
        $this->expectException(MethodNotSupported::class);
        Route::run('/testMethod', 'PUT');
    }
}
