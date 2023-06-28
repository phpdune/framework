<?php

/*
 * This file is part of Dune Framework.
 *
 * (c) Abhishek B <phpdune@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Dune\Routing;

use Dune\Routing\RouterContainer;
use Dune\Routing\Exception\ClassNotFound;
use Dune\Routing\Exception\MethodNotFound;
use Dune\Views\View;
use Dune\Http\Request;
use Dune\Routing\Controller;
use Dune\Routing\Exception\InvalidController;

class RouteActionCaller
{
    use RouterContainer;

    /**
     * calling trait method
     */
    public function __construct()
    {
        $this->__setUp();
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
        $params = RouteResolver::$params;
        return $this->container->call($action, $params);
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
        return view($file);
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
            $class = $this->container->get($class);
            if(!$class instanceof Controller) {
                throw new InvalidController("Cannot resolve this class, this class must implements routing controller interface", 500);
            }
        } else {
            throw new ClassNotFound("Exception : Class {$class} Not Found", 404);
        }
        if (method_exists($class, $method)) {
            $params = RouteResolver::$params;
            return $this->container->call([$class,$method], $params);

        }
        throw new MethodNotFound("Exception : Method {$method} Not Found", 404);
    }
}
