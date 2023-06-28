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

use Dune\Routing\RouteResolver;
use Dune\Routing\RouterInterface;
use Closure;
use ReflectionAttribute;
use ReflectionClass;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Dune\Routing\Attributes\Route;
use Dune\Routing\Controller;
use Dune\Routing\Exception\InvalidController;

class Router implements RouterInterface
{
    /**
     * route prefix
     *
     * @var string
     */
    private string $prefixedUrl = '';
    /**
     * The all routes stored here.
     *
     * @var array<mixed>
     */
    public static array $routes = [];
    /**
     * prefix controller
     *
     * @var string
     */
    protected string $controller;
    /**
     * mapped controller name's from controller folder
     *
     * @var array<int,string>
     */
    protected array $controllerMap = [];
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
     * @param RouteResolver $resolver
     */
    public function __construct(RouteResolver $resolver)
    {
        $this->resolver = $resolver;
    }
    /**
     * set the routes
     *
     * @param string $route
     * @param string $method
     * @param mixed $action
     *
     */
    protected function setRoutes(
        string $route,
        string $method,
        $action
    ): void {
        self::$path = $route;
        self::$routes[] = [
            'route' => $route,
            'method' => $method,
            'action' => $action
        ];
    }
    /**
     * set the routes by attributes
     */
    private function setAttributeRoute(): void
    {
        $controllers = $this->mapControllers();
        foreach($controllers as $controller) {
            $reflection = new ReflectionClass($controller);
            if(!$reflection->implementsInterface(Controller::class)) {
                throw new InvalidController("Cannot resolve this class, this class must implements routing controller interface");
            }
            foreach($reflection->getMethods() as $method) {
                $attributes = $method->getAttributes(Route::class, ReflectionAttribute::IS_INSTANCEOF);

                foreach($attributes as $attribute) {
                    $route = $attribute->newInstance();

                    $this->setRoutes($route->path, $route->method, [$controller, $method->getName()]);
                    $this->setAttributePartials($route);
                }
            }
        }
    }
    /**
     * set the route partials by attributes
     *
     * @param mixed $route
     */
    private function setAttributePartials(mixed $route): void
    {
        if(!is_null($route->name)) {
            self::$names[$route->name] = $route->path;
        }
        if(!is_null($route->middleware)) {
            self::$middlewares[$route->path] = $route->middleware;
        }
    }
    /**
     * @param string $route
     * @param mixed $action
     *
     * @return self
     */
    public function get(string $route, $action): self
    {
        if (is_string($action)) {
            $action = [$this->controller,$action];
        }
        $route = $this->prefixedUrl . $route;
        $this->setRoutes($route, 'GET', $action);
        return $this;
    }
    /**
     * @param string $route
     * @param string $view
     *
     * @return self
     */
    public function view(string $route, string $view): self
    {
        $route = $this->prefixedUrl . $route;
        $this->setRoutes($route, 'GET', $view);
        return $this;
    }
    /**
     * @param string $route
     * @param mixed $action
     *
     * @return self
     */
    public function post(string $route, $action): self
    {
        if (is_string($action)) {
            $action = [$this->controller,$action];
        }
        $route = $this->prefixedUrl . $route;
        $this->setRoutes($route, 'POST', $action);
        return $this;
    }
    /**
     * @param string $route
     * @param mixed $action
     *
     * @return self
     */
    public function put(string $route, $action): self
    {
        if (is_string($action)) {
            $action = [$this->controller,$action];
        }
        $route = $this->prefixedUrl . $route;
        $this->setRoutes($route, 'PUT', $action);
        return $this;
    }
    /**
     * @param string $route
     * @param mixed $action
     *
     * @return self
     */
    public function patch(string $route, $action): self
    {
        if (is_string($action)) {
            $action = [$this->controller,$action];
        }
        $route = $this->prefixedUrl . $route;
        $this->setRoutes($route, 'PATCH', $action);
        return $this;
    }
    /**
     * @param string $route
     * @param mixed $action
     *
     * @return self
     */
    public function delete(string $route, $action): self
    {
        if (is_string($action)) {
            $action = [$this->controller,$action];
        }
        $route = $this->prefixedUrl . $route;
        $this->setRoutes($route, 'DELETE', $action);
        return $this;
    }

    /**
     * set name for routes
     *
     * @param string $name
     *
     * @return self
     */
    public function name(string $name): self
    {
        self::$names[$name] = self::$path;
        return $this;
    }
    /**
     * set middlware for route
     *
     * @param string $key
     *
     * @return self
     */
    public function middleware(string $key): self
    {
        self::$middlewares[self::$path] = $key;
        return $this;
    }
    /**
     * trigger the route attributes mode by this
     */
    public function useAttributes(): void
    {
        $this->setAttributeRoute();
    }
    /**
     * proceeds the route to run
     *
     * @param string $uri
     * @param string $method
     *
     * @throw \MethodNotSupported
     * @throw \NotFound
     *
     * @return string|null
     */
    public function dispatch(string $uri, string $method): mixed
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
    public function controller(string $controller, Closure $callback): void
    {
        $this->controller = $controller;
        $callback();
    }
    /**
     * route url grouping
     *
     * @param string $prefix
     * @param \Closure $callback
     *
     */
    public function prefix(string $prefix, Closure $callback): void
    {
        $oldPrefix = $this->prefixedUrl;
        $this->prefixedUrl = $oldPrefix . $prefix;
        $callback();
        $this->prefixedUrl = $oldPrefix;
    }
    /**
     * get the controllers from controller dir
     *
     * @return array<int,string>
     */
    private function mapControllers(): array
    {
        if(!empty($this->controllerMap)) {
            return $this->controllerMap;
        }
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(PATH.'/app/controllers'));

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $name = str_replace(PATH, '', $file->getPathname());
                $name = str_replace('.php', '', $name);
                $name = str_replace('/', ' ', $name);
                $name = ucwords($name);
                $name = str_replace(' ', '\\', $name);
                $this->controllerMap[] = $name;
            }
        }
        return $this->controllerMap;
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
    /**
     * clear all properties values
     *
     * @return void
     */
    public function clear(): void
    {
        self::$routes = [];
        self::$middlewares = [];
        self::$names = [];
        self::$path = '';
        $this->prefixedUrl = '';
        $this->controller = '';
        $this->controllerMap = [];

    }
}
