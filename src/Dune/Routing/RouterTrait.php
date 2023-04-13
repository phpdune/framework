<?php

declare(strict_types=1);

namespace Dune\Routing;

trait RouterTrait 
{
   protected static function initHandler()
   {
      return new \Dune\Routing\RouteHandler(
        new \Dune\Routing\RouteResolver()
        );
   }
   protected function initActionCaller()
   {
     return new \Dune\Views\View();
   }
}