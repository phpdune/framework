<?php

declare(strict_types=1);

namespace Dune\Facades;

use Dune\Facades\Facade;

class Csrf extends Facade
{
    /**
     * accesser to resolve the specific instance
     *
     * @return string
     */
    protected static function getAccessor(): string
    {
        return 'csrf';
    }
}
