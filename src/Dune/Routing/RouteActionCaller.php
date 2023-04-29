<?php

declare(strict_types=1);

namespace Dune\Routing;

use Dune\Routing\RouterTrait;
use Dune\Routing\Exception\ClassNotFound;
use Dune\Routing\Exception\MethodNotFound;
use Dune\Views\View;
use Dune\Http\Request;
use Dune\App;

class RouteActionCaller
{
    /**
     * view instance
     *
     * @var ?View
     */
    private ?View $view = null;
    /**
     * view instance setting
     */
    public function __construct(View $view)
    {
        if (is_null($this->view)) {
            $this->view = $view;
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
        $container = App::container();
        $params = RouteResolver::$params;
        return $container->call($action, $params);
    }
    /**
     * will render the view calling from the route
     *
     * @param  string  $file.
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
     * @param  array<string,string> $action
     *
     * @throw \Dune\Routing\Exception\NotFound
     *
     * @return string|null
     */
    protected function runMethod(array $action): mixed
    {
        [$class, $method] = $action;
        if (class_exists($class)) {
            $container = App::container();
            $class = $container->get($class);
        } else {
            throw new ClassNotFound("Exception : Class {$class} Not Found");
        }
        if (method_exists($class, $method)) {
            $params = RouteResolver::$params;
            return $container->call([$class,$method], $params);

        }
        throw new MethodNotFound("Exception : Method {$method} Not Found");
    }
}
