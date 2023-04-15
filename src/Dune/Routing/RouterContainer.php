<?php

declare(strict_types=1);

namespace Dune\Routing;

use Dune\Routing\RouteHandler;

trait RouterContainer
{
    protected static function init()
    {
        if (!self::$route) {
            $container = new \Dune\Container\Container();
            self::$route = $container->get(RouteHandler::class);
        }
    }
}
