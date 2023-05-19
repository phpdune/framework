<?php

declare(strict_types=1);

namespace Dune\Routing\Attributes;

use Attribute;
use Dune\Routing\Attributes\Route;

#[Attribute(Attribute::TARGET_METHOD|Attribute::IS_REPEATABLE)]
class Patch extends Route
{
    /**
     * route details setting for PATCH method
     *
     * @param string $path
     * @param ?string $name
     * @param ?string $middleware
     *
     */
    public function __construct(string $path, string $name = null, string $middleware = null)
    {
        parent::__construct('PATCH', $path, $name, $middleware);
    }
}