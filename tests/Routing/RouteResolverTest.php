<?php

namespace Dune\Tests;

use PHPUnit\Framework\TestCase;
use Dune\Routing\RouteResolver;
use Dune\Routing\Exception\RouteNotFound;
use Dune\Routing\Exception\MethodNotSupported;
use Dune\Container\Container;
use Dune\Routing\Router as Route;

class RouteResolverTest extends TestCase
{
    protected $resolver;

    protected function setUp(): void
    {
        $container = new Container();
        $this->resolver = $container->get(RouteResolver::class);
    }
    public function testMethodNotSupportedExceptionThrow(): void
    {
        Route::get('/', function () {});
        $this->expectException(MethodNotSupported::class);
        $this->resolver->resolve('/', 'POST');
    }
    public function testRouteNotFoundExceptionThrow(): void
    {
        $this->expectException(RouteNotFound::class);
        $this->resolver->resolve('/hello', 'GET');
    }
    public function testGetParams(): void
    {
        $expected = RouteResolver::$params;
        $value = $this->resolver->getParams();
        $this->assertSame($expected, $value);
    }
}
