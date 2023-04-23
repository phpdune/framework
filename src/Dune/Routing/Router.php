<?php

declare(strict_types=1);

namespace Dune\Routing;

use App\Middleware\Middleware;
use Dune\Views\View;
use Dune\Routing\RouterTrait;

class Router implements RouterInterface
{
    use RouterContainer;

    /**
     * \Dune\Routing\RouteHandler instance
     *
     * @var ?RouteHandler
     */
    private static ?RouteHandler $route = null;
    /**
     * @param  string  $route
     * @param  callable|array<string,string>|string  $action
     *
     * @return self
     */

    public static function get(string $route, callable|array|string $action): self
    {
        self::init();
        self::$route->getHandler($route, $action);
        return new self();
    }
    /**
     * @param  string  $route
     * @param  string  $view
     *
     * @return self
     */
    public static function view(string $route, string $view): self
    {
        self::init();
        self::$route->viewHandler($route, $view);
        return new self();
    }
    /**
     * @param  string  $route
     * @param  callable|array<string,string>|string  $action
     *
     * @return self
     */
    public static function post(string $route, callable|array|string $action): self
    {
        self::init();
        self::$route->postHandler($route, $action);
        return new self();
    }
    /**
     * @param  string  $route
     * @param  callable|array<string,string>|string  $action
     *
     * @return self
     */
    public static function put(string $route, callable|array|string $action): self
    {
        self::init();
        self::$route->putHandler($route, $action);
        return new self();
    }
    /**
     * @param  string  $route
     * @param  callable|array<string,string>|string  $action
     *
     * @return self
     */
    public static function patch(string $route, callable|array|string $action): self
    {
        self::init();
        self::$route->patchHandler($route, $action);
        return new self();
    }
    /**
     * @param  string  $route
     * @param  callable|array<string,string>|string  $action
     *
     * @return self
     */
    public static function delete(string $route, callable|array|string $action): self
    {
        self::init();
        self::$route->deleteHandler($route, $action);
        return new self();
    }

    /**
     *
     * @param  string  $name
     *
     * @return self
     */
    public static function name(string $name): self
    {
        self::init();
        self::$route->setName($name);
        return new self();
    }
    /**
     *
     * @param  string  $key
     *
     * @return self
     */
    public function middleware(string $key): self
    {
        self::init();
        self::$route->setMiddleware($key);
        return new self();
    }

    /**
     * route controller grouping
     *
     * @param  string  $controller
     * @param  \Closure  $callback
     *
     */
     public static function controller(string $controller, \Closure $callback): void
     {
         self::init();
         self::$route->setControllerPrefix($controller, $callback);
     }

    /**
     * route handling
     *
     * @param string $uri
     * @param string $method
     *
     * @return mixed
     */
     public static function run(string $uri, string $method): mixed
     {
         self::init();
         return self::$route->run($uri, $method);
     }
}
