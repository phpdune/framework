<?php

namespace Dune\Tests\Unit\Routing;

use PHPUnit\Framework\TestCase;
use Dune\Tests\Bootstrap;
use Dune\Routing\RouteLoader;
use Dune\Routing\Router;
use Dune\Tests\Unit\Routing\Controller\TestController;
use Dune\Http\Request;

class RouteDITest extends TestCase
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

    public function test_di_of_object_to_closure(): void
    {
        $this->router->get('/', function (Request $request) {
            return $request;
        });
        $result = $this->router->dispatch('/', 'GET');
        $expected = new Request();
        $this->assertEquals($expected, $result);
    }
   public function test_di_of_str_to_closure(): void
   {
       $this->router->get('/{id}', function ($id) {
           return $id;
       });
       $this->router->get('/test/{id}', function ($id) {
           return $id;
       });
       //test 1
       $result = $this->router->dispatch('/test', 'GET');
       $expected = 'test';
       $this->assertEquals($expected, $result);
       // test 2
       $result = $this->router->dispatch('/test/1', 'GET');
       $expected = '1';
       $this->assertEquals($expected, $result);
   }
   public function test_di_of_multiple_str_to_closure(): void
   {
       $this->router->get('/{id}/{token}', function ($id, $token) {
           return $id .'|'. $token;
       });
       $this->router->get('/test/{id}/{token}', function ($id, $token) {
           return $id .'|'. $token;
       });
       //test 1
       $result = $this->router->dispatch('/test/dune', 'GET');
       $expected = 'test|dune';
       $this->assertEquals($expected, $result);
       // test 2
       $result = $this->router->dispatch('/test/1/dune', 'GET');
       $expected = '1|dune';
       $this->assertEquals($expected, $result);
   }
   public function test_di_str_to_controller(): void
   {
       $this->router->get('/{id}/{token}', [TestController::class,'testdi']);
       $result = $this->router->dispatch('/23/test', 'GET');
       $expected = 'success';
       $this->assertEquals($expected, $result);
   }
    public function test_di_object_and_str_to_controller(): void
    {
        $this->router->get('/{id}', [TestController::class,'testdi2']);
        $result = $this->router->dispatch('/23', 'GET');
        $expected = 'ok';
        $this->assertEquals($expected, $result);
    }
}
