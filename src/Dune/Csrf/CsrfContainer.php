<?php

declare(strict_types=1);

namespace Dune\Csrf;

use Dune\Csrf\CsrfHandler;

trait CsrfContainer 
{
  protected static function init()
  {
    $container = new \Dune\Container\Container();
     if(is_null(self::$handler)) {
       self::$handler = $container->get(CsrfHandler::class);
     }
  }
}