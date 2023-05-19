<?php

declare(strict_types=1);

namespace Dune\Facades;

use Dune\Facades\Facade;

class Route extends Facade
{
    /**
     * accesser to resolve the specific instance
     *
     * @return string
     */
    protected static function getAccessor(): string
    {
        return 'route';
    }
}
