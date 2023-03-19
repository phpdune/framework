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
     * The route path
     *
     * @var string
     */
    public static string $name;

    /**
     * The routes name stored here
     *
     * @var array
     */
    public static array $names;

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
        self::$name = $route;
        self::$routes[] = [
            'route' => $route,
            'method' => $method,
            'action' => $action,
            'middleware' => null,
        ];
    }
    /**
     * @param  string  $route
     * @param  callable|array  $action
     *
     * @return static
     */
    public static function get(string $route, callable|array $action): self
    {
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
    public static function post(string $route, callable|array $action): self
    {
        self::setRoutes($route, 'POST', $action);
        return new static();
    }
    /**
     * @param  string  $route
     * @param  callable|array  $action
     *
     * @return static
     */
    public static function put(string $route, callable|array $action): self
    {
        self::setRoutes($route, 'PUT', $action);
        return new static();
    }
    /**
     * @param  string  $route
     * @param  callable|array  $action
     *
     * @return static
     */
    public static function patch(string $route, callable|array $action): self
    {
        self::setRoutes($route, 'PATCH', $action);
        return new static();
    }
    /**
     * @param  string  $route
     * @param  callable|array  $action
     *
     * @return static
     */
    public static function delete(string $route, callable|array $action): self
    {
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
    protected static function runCallable(callable $action): ?string
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
        self::$names[$name] = self::$name;
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
        self::$routes[array_key_last(self::$routes)]["middleware"] = $key;
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
    protected static function renderView(string $action): null
    {
        return View::render($action);
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
     public static function run($uri, $method): ?string
     {
         return Action::tryRun($uri, $method);
     }
}
