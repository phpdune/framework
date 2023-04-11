<?php

declare(strict_types=1);

namespace Dune\Routing;

use App\Middleware\Middleware;
use Dune\Views\View;

class Router implements RouterInterface
{   /**
     * The all routes stored here.
     *
     * @var array
     */
    protected static array $routes;
    /**
     * prefix controller
     *
     * @var string
     */
    private static string $controller;

    /**
     * The route path
     *
     * @var string
     */
    public static string $path;

    /**
     * The routes name stored here
     *
     * @var array
     */
    public static array $names;
    /**
     * route middlewares storred here
     *
     * @var array
     */
    protected static array $middlewares;
    /**
     * route params storred here
     *
     * @var array
     */
    protected static array $params = [];
    /**
     * @param  string  $route
     * @param  string  $method
     * @param callable|string $action
     *
     * @return none
     */
    public static function setRoutes(
        string $route,
        string $method,
        callable|array|string $action
    ): void {
        self::$path = $route;
        self::$routes[] = [
            'route' => $route,
            'method' => $method,
            'action' => $action
        ];
    }
    /**
     * @param  string  $route
     * @param  callable|array  $action
     *
     * @return static
     */
    public static function get(string $route, callable|array|string $action): self
    {
        if (is_string($action)) {
            $action = [self::$controller,$action];
        }
        self::setRoutes($route, 'GET', $action);
        return new static();
    }
    /**
     * @param  string  $route
     * @param  string  $view
     *
     * @return static
     */
    public static function view(string $route, string $view): self
    {
        self::setRoutes($route, 'GET', $view);
        return new static();
    }
    /**
     * @param  string  $route
     * @param  callable|array  $action
     *
     * @return static
     */
    public static function post(string $route, callable|array|string $action): self
    {
        if (is_string($action)) {
            $action = [self::$controller,$action];
        }
        self::setRoutes($route, 'POST', $action);
        return new static();
    }
    /**
     * @param  string  $route
     * @param  callable|array  $action
     *
     * @return static
     */
    public static function put(string $route, callable|array|string $action): self
    {
        if (is_string($action)) {
            $action = [self::$controller,$action];
        }
        self::setRoutes($route, 'PUT', $action);
        return new static();
    }
    /**
     * @param  string  $route
     * @param  callable|array  $action
     *
     * @return static
     */
    public static function patch(string $route, callable|array|string $action): self
    {
        if (is_string($action)) {
            $action = [self::$controller,$action];
        }
        self::setRoutes($route, 'PATCH', $action);
        return new static();
    }
    /**
     * @param  string  $route
     * @param  callable|array  $action
     *
     * @return static
     */
    public static function delete(string $route, callable|array|string $action): self
    {
        if (is_string($action)) {
            $action = [self::$controller,$action];
        }
        self::setRoutes($route, 'DELETE', $action);
        return new static();
    }

    /**
     * will run callable action in route
     *
     * @param  callable  $action
     *
     * @return string|null
     */
    protected static function runCallable(callable $action): mixed
    {
        return call_user_func($action);
    }

    /**
     *
     * @param  string  $name
     *
     * @return static
     */
    public static function name(string $name): self
    {
        self::$names[$name] = self::$path;
        return new static();
    }
    /**
     *
     * @param  string  $key
     *
     * @return static
     */
    public function middleware(string $key): self
    {
        self::$middlewares[self::$path] = $key;
        return new static();
    }
    /**
     * middleware calling method
     *
     * @param  string  $middleware.
     *
     * @return none
     */
    protected static function callMiddleware(string $middleware): void
    {
        (new $middleware())->handle();
    }
    /**
     * will render the view calling from the route
     *
     * @param  string  $mi.
     *
     * @return null
     */
    protected static function renderView(string $file): null
    {
        return View::render($file);
    }
    /**
     * proceeds the route to run
     *
     * @param  string  $uri
     * @param  string  $requestMethod
     *
     * @throw \MethodNotSupported
     * @throw \NotFound
     *
     * @return string|null
     */
     public static function run($uri, $method): mixed
     {
         return Action::tryRun($uri, $method);
     }
    /**
     * route controller grouping
     *
     * @param  string  $controller
     * @param  \Closure  $callback
     *
     * @return none
     */
     public static function controller(string $controller, \Closure $callback): void
     {
         self::$controller = $controller;
         $callback();
     }
}
