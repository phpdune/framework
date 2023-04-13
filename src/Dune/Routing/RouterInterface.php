<?php

declare(strict_types=1);

namespace Dune\Routing;

interface RouterInterface
{
    /**
     * Register the GET route
     *
     * @param  string  $route
     * @param  callable|array  $action
     *
     * @return static
     */
    public static function get(string $route, callable|array $action): self;
    /**
     * Register the POST route
     *
     * @param  string  $route
     * @param  callable|array  $action
     *
     * @return static
     */
    public static function post(string $route, callable|array $action): self;
    /**
     * Register the PUT route
     *
     * @param  string  $route
     * @param  callable|array  $action
     *
     * @return static
     */
    public static function put(string $route, callable|array $action): self;
    /**
     * Register the PACTH route
     *
     * @param  string  $route
     * @param  callable|array  $action
     *
     * @return static
     */
    public static function patch(string $route, callable|array $action): self;
    /**
     * Register the DELETE route
     *
     * @param  string  $route
     * @param  callable|array  $action
     *
     * @return static
     */
    public static function delete(string $route, callable|array $action): self;
    /**
     * Register the view route, method will be GET
     *
     * @param  string  $route
     * @param  string  $view
     *
     * @return static
     */
    public static function view(string $route, string $view): self;
    /**
     * route name registering will happen here
     *
     * @param  string  $name
     *
     * @return static
     */
    public static function name(string $name): self;
}
