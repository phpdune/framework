<?php

declare(strict_types=1);

namespace Dune\Routing;

use Dune\Routing\RouteHandler;
use Dune\App;

trait RouterContainer
{
    protected static function init(): void
    {
        if (is_null(self::$route)) {
            $container = App::container();
            self::$route = $container->get(RouteHandler::class);
        }
    }
}
