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

use Closure;

interface RouterInterface
{
    /**
     * Register the GET route
     *
     * @param  string  $route
     * @param  array<string,string>|Closure|string $action
     *
     * @return self
     */
    public function get(string $route, Closure|array|string $action): self;
    /**
     * Register the POST route
     *
     * @param  string  $route
     * @param  Closure|array<string,string>|string  $action
     *
     * @return self
     */
    public function post(string $route, Closure|array|string $action): self;
    /**
     * Register the PUT route
     *
     * @param  string  $route
     * @param  Closure|string|array<string,string>  $action
     *
     * @return self
     */
    public function put(string $route, Closure|array|string $action): self;
    /**
     * Register the PACTH route
     *
     * @param  string  $route
     * @param  Closure|string|array<string,string> $action
     *
     * @return self
     */
    public function patch(string $route, Closure|array|string $action): self;
    /**
     * Register the DELETE route
     *
     * @param  string  $route
     * @param  Closure|string|array<string,string> $action
     *
     * @return self
     */
    public function delete(string $route, Closure|string|array $action): self;
    /**
     * Register the view route, method will be GET
     *
     * @param  string  $route
     * @param  string  $view
     *
     * @return self
     */
    public function view(string $route, string $view): self;
    /**
     * route name registering will happen here
     *
     * @param  string  $name
     *
     * @return self
     */
    public function name(string $name): self;
}
