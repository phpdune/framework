<?php

declare(strict_types=1);

namespace Dune\Routing;

use Dune\Http\Request;
use Dune\Routing\Exception\RouteNotFound;
use Dune\Routing\Exception\MethodNotSupported;
use Dune\Routing\Exception\MiddlewareNotFound;
use Dune\Routing\RouteActionCaller;
use Dune\Routing\Router as Route;
use Dune\Http\Middleware\Middleware;
use Dune\Http\Middleware\MiddlewareStack;

class RouteResolver extends RouteActionCaller
{
    /**
     * route parama storred here
     *
     * @var array<string,mixed>
     */
    public static array $params = [];

    /**
     * Check the route exist and pass to other method to run,
     *
     * @param  string  $uri
     * @param  string  $requestMethod
     *
     * @throw \Dune\Routing\Exception\MethodNotSupported
     * @throw \Dune\Routing\Exception\RouteNotFound
     *
     * @return string|null
     */
    public function resolve(string $uri, string $requestMethod): mixed
    {
        $url = parse_url($uri);

        foreach (Route::$routes as $route) {
            $regex = preg_replace('/\{([a-z]+)\}/', '(?P<$1>[a-zA-Z0-9]+)', $route['route']);
            $regex = str_replace('/', '\/', $regex);
            if (preg_match('/^' . $regex . '$/', $url['path'], $matches) && $route["method"] != $requestMethod) {
                throw new MethodNotSupported(
                    "Exception : {$requestMethod} Method Not Supported For This Route, Supported Method {$route["method"]}"
                );
            }
            if (preg_match('/^' . $regex . '$/', $url['path'], $matches) && $route["method"] == $requestMethod) {
                $key = Route::$middlewares[$route['route']] ?? null;
                $middlewares = $this->getMiddleware($key);
                $this->runMiddlewares($middlewares);

                $action = $route["action"];

                foreach ($matches as $key => $value) {
                    if(is_string($key)) {
                        self::$params[$key] = $value;
                    }

                }
                if (is_callable($action)) {
                    return $this->runCallable($action);
                }
                if (is_array($action)) {
                    return $this->runMethod($action);
                }
                if (is_string($action)) {
                    return $this->renderView($action);
                }
            }
        }
        throw new RouteNotFound(
            "Exception : Route Not Found By This URI {$url["path"]}"
        );
    }
    /**
     * get the middleware
     *
     * @param  string  $middleware
     *
     *
     * @return string|null
     */
    protected function getMiddleware(?string $middleware): ?array
    {
        if(class_exists(\App\Middleware\RegisterMiddleware::class)) {
            $middlewareBag = new \App\Middleware\RegisterMiddleware();
            $default = $middlewareBag->defaultMiddlewares;
            if(is_null($middleware)) {
                return $default;
            }
            $middlewares = $middlewareBag->middleware;
            foreach($middlewares as $key => $value) {
                if($key == $middleware) {
                    return array_merge($default, $value);
                }
                throw new MiddlewareNotFound(
                    "Exception : Middleware {$middleware} Not Found"
                );
            }
        }
        return null;
    }
    /**
     * return params
     *
     * @return array<string,string>|null
     */
     public function getParams(): ?array
     {
         return self::$params;
     }
    /**
     * middleware calling method
     *
     * @param  string  $middleware.
     *
     */
    protected function runMiddlewares(array $middlewares): void
    {
        $middlewareDispatcher = new Middleware(new MiddlewareStack());
        if(is_array($middlewares)) {
            foreach($middlewares as $middleware) {
                $middlewareDispatcher->add(new $middleware());
            }
            $middlewareDispatcher->run();
        }
    }
}
