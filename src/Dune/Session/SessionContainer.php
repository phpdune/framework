<?php

declare(strict_types=1);

namespace Dune\Session;

trait SessionContainer 
{
  public static function init(): void 
  {
       if(!self::$handler) {
       $container = new \Dune\Container\Container();
       self::$handler = $container->get(SessionHandler::class);
       }
     }
}