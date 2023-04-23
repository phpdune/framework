<?php

declare(strict_types=1);

namespace Dune\Routing;

class RouteHandler
{
    /**
     * The all routes stored here.
     *
     * @var array<mixed>
     */
    public static array $routes;
    /**
     * prefix controller
     *
     * @var string
     */
    protected string $controller;

    /**
     * The route path
     *
     * @var string
     */
    public static string $path;

    /**
     * The routes name stored here
     *
     * @var array<string,string>
     */
    public static array $names = [];
    /**
     * route middlewares storred here
     *
     * @var array<string,string>
     */
    public static array $middlewares = [];

    /**
     * route resolver instance
     *
     * @var ?RouteResolver
     */
    private ?RouteResolver $resolver = null;

    /**
     * resolver instance setting
     *
     */
    public function __construct(RouteResolver $resolver)
    {
        $this->resolver = $resolver;
    }
    /**
     * @param  string  $route
     * @param  string  $method
     * @param callable|string|array<string,string> $action
     *
     */
    protected function setRoutes(
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
     * @param  callable|array<string,string>|string  $action
     *
     */
     public function getHandler(string $route, callable|array|string $action): void
     {
         if (is_string($action)) {
             $action = [$this->controller,$action];
         }
         $this->setRoutes($route, 'GET', $action);
     }
    /**
     * @param  string  $route
     * @param  string  $view
     *
     */
    public function viewHandler(string $route, string $view): void
    {
        $this->setRoutes($route, 'GET', $view);
    }
    /**
     * @param  string  $route
     * @param  callable|array<string,string>|string  $action
     *
     */
     public function postHandler(string $route, callable|array|string $action): void
     {
         if (is_string($action)) {
             $action = [$this->controller,$action];
         }
         $this->setRoutes($route, 'POST', $action);
     }
    /**
     * @param  string  $route
     * @param  callable|array<string,string>|string  $action
     *
     */
     public function putHandler(string $route, callable|array|string $action): void
     {
         if (is_string($action)) {
             $action = [$this->controller,$action];
         }
         $this->setRoutes($route, 'PUT', $action);
     }
    /**
     * @param  string  $route
     * @param  callable|array<string,string>|string  $action
     *
     */
     public function patchHandler(string $route, callable|array|string $action): void
     {
         if (is_string($action)) {
             $action = [$this->controller,$action];
         }
         $this->setRoutes($route, 'PATCH', $action);
     }
    /**
     * @param  string  $route
     * @param  callable|array<string,string>|string  $action
     *
     */
     public function deleteHandler(string $route, callable|array|string $action): void
     {
         if (is_string($action)) {
             $action = [$this->controller,$action];
         }
         $this->setRoutes($route, 'DELETE', $action);
     }

    /**
     *
     * @param  string  $name
     *
     */
    public function setName(string $name): void
    {
        self::$names[$name] = self::$path;
    }
    /**
     *
     * @param  string  $key
     *
     */
    public function setMiddleware(string $key): void
    {
        self::$middlewares[self::$path] = $key;
    }

    /**
     * proceeds the route to run
     *
     * @param  string  $uri
     * @param  string  $method
     *
     * @throw \MethodNotSupported
     * @throw \NotFound
     *
     * @return string|null
     */
     public function run(string $uri, string $method): mixed
     {
         return $this->resolver->resolve($uri, $method);
     }
    /**
     * route controller grouping
     *
     * @param  string  $controller
     * @param  \Closure  $callback
     *
     */
     public function setControllerPrefix(string $controller, \Closure $callback): void
     {
         $this->controller = $controller;
         $callback();
     }

    /**
     * return routes
     *
     * @return array<string,string>|null
     */
     public function getRoutes(): ?array
     {
         return self::$routes;
     }
    /**
     * return path
     *
     * @return string|null
     */
     public function getPath(): ?string
     {
         return self::$path;
     }
    /**
     * return names
     *
     * @return array<string,string>|null
     */
     public function getNames(): ?array
     {
         return self::$names;
     }
    /**
     * return middleware
     *
     * @param string $middleware
     *
     * @return string|null
     */
     public function getMiddleware(string $middleware): ?string
     {
         return (isset(self::$middlewares[$middleware]) ? self::$middlewares[$middleware] : null);
     }

    /**
     * return true if route has middleware
     *
     * @param string $middleware
     *
     * @return bool
     */
     public function hasMiddleware(string $middleware): bool
     {
         return (isset(self::$middlewares[$middleware]) ? true : false);
     }
}
