<?php

declare(strict_types=1);

namespace Dune\Session;

use Dune\Session\SessionHandler;
use Dune\App;

trait SessionContainer
{
    public static function init(): void
    {
        if (!self::$handler) {
            $container = App::container();
            self::$handler = $container->get(SessionHandler::class);
        }
    }
}
