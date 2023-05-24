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

namespace Dune\Routing\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD|Attribute::IS_REPEATABLE)]
class Route
{
    /**
     * route method
     *
     * @var string
     */
    public string $method;
    /**
     * route path
     *
     * @var string
     */
    public string $path;
    /**
     * route name
     *
     * @var string
     */
    public ?string $name;
    /**
     * route middleware
     *
     * @var string
     */
    public ?string $middleware;
    /**
     * route details setting
     *
     * @param string $method
     * @param string $path
     * @param string $name
     * @param string $middleware
     *
     */
    public function __construct(
        string $method,
        string $path,
        ?string $name = null,
        ?string $middleware = null
    ) {
        $this->method = $method;
        $this->path = $path;
        $this->name = $name;
        $this->middleware = $middleware;
    }
}
