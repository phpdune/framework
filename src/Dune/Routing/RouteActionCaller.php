<?php

declare(strict_types=1);

namespace Dune\Routing;

use Dune\Routing\RouterTrait;
use Dune\Exception\NotFound;
use Dune\Container\Container;
use Dune\Views\View;
use Dune\Http\Request;

class RouteActionCaller 
{
    use RouterTrait;
    
    /**
     * view instance
     *
     * @var array
     */
     private ?View $view = null;
    /**
     * @param  none
     *
     * @return none
     */     
     public function __construct()
     {
       if(is_null($this->view)) {
         $this->view = self::initActionCaller();
       }
     }
    /**
     * will run callable action in route
     *
     * @param  callable  $action
     *
     * @return string|null
     */
    protected function runCallable(callable $action): mixed
    {
        return call_user_func($action);
    }
    /**
     * will render the view calling from the route
     *
     * @param  string  $mi.
     *
     * @return null
     */
    protected function renderView(string $file): null
    {
        return $this->view->render($file);
    }
    /**
     * will run method in route
     *
     * @param  array  $action
     *
     * @throw \NotFound
     *
     * @return string|null
     */
    protected function runMethod(array $action): mixed
    {
        [$class, $method] = $action;
        if (class_exists($class)) {
            $container = new Container();
            $class = $container->get($class);
        } else {
            throw new NotFound("Exception : Class {$class} Not Found");
        }
        if (method_exists($class, $method)) {
              $request = new Request();
              $request->setParams(RouteResolver::$params);
              
            return call_user_func_array([$class, $method], [$request]);
        }
        throw new NotFound("Exception : Method {$method} Not Found");
    }
}