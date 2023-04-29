<?php

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
