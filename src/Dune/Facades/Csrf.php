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
