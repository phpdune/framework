<?php

declare(strict_types=1);

namespace Dune\Csrf;

use Dune\Csrf\CsrfHandler;
use Dune\App;

trait CsrfContainer
{
    protected static function init(): void
    {
        $container = App::container();
        if(is_null(self::$handler)) {
            self::$handler = $container->get(CsrfHandler::class);
        }
    }
}
