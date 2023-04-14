<?php

declare(strict_types=1);

namespace Dune\Routing;

use Dune\Http\Request;
use Dune\Routing\Exception\RouteNotFound;
use Dune\Routing\Exception\MethodNotSupported;
use Dune\Csrf\Csrf;
use Dune\Session\Session;
use Dune\Routing\RouteActionCaller;

class RouteResolver extends RouteActionCaller
{
    /**
     * route parama storred here
     *
     * @var array
     */
     public static array $params = [];
     
    /**
     * Check the route exist and pass to other method to run,
     *
     * @param  string  $uri
     * @param  string  $requestMethod
     *
     * @throw \MethodNotSupported
     * @throw \RouteNotFoundException
     *
     * @return string|null
     */
    public function resolve(string $uri, string $requestMethod): mixed
    {
        $url = parse_url($uri);

        foreach (RouteHandler::$routes as $route) {
            $regex = preg_replace('/\{([a-z]+)\}/', '(?P<$1>[a-zA-Z0-9]+)', $route['route']);
            $regex = str_replace('/', '\/', $regex);
            if (
                preg_match('/^' . $regex . '$/', $url['path'], $matches) &&
                $route["method"] != $requestMethod
            ) {
                throw new MethodNotSupported(
                    "Exception : {$requestMethod} Method Not Supported For This Route, Supported Method {$route["method"]}"
                );
            }
            if (
                preg_match('/^' . $regex . '$/', $url['path'], $matches) &&
                $route["method"] == $requestMethod
            ) {
                if ($requestMethod != "GET") {
                    $request = new Request();
                    if (
                        !Csrf::validate(
                            Session::get("_token"),
                            $request->get("_token")
                        )
                    ) {
                        abort(419, "Page Expired");
                        exit();
                    }
                }
                $action = $route["action"];
                if (RouteHandler::$middlewares[$route['route']]) {
                    $middleware = $this->getMiddleware(RouteHandler::$middlewares[
                      $route['route']]);
                    $this->callMiddleware($middleware);
                }
             foreach ($matches as $key => $value) {
                 self::$params[$key] = $value;
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
    public function getMiddleware(string $middleware): ?string
    {
        $middleware = \App\Middleware\RegisterMiddleware::MAP[$middleware] ?? null;
        if (!$middleware) {
            throw new RouteNotFound(
                "Exception : Middleware {$route["middleware"]} Not Found"
            );
        }
        return $middleware;
    }
    /**
     * return params
     *
     * @param none
     *
     * @return array|null
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
     * @return none
     */
    public function callMiddleware(string $middleware): void
    {
        (new $middleware())->handle();
    }
}