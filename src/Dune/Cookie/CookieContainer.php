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

namespace Dune\Cookie;

use Dune\Cookie\CookieHandler;
use Dune\App;

trait CookieContainer
{
    public static function init(): void
    {
        if (is_null(self::$handler)) {
            $container = App::container();
            self::$handler = $container->get(CookieHandler::class);
        }
    }
}
