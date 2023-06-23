<?php

namespace Dune\Tests;

use Illuminate\Container\Container;
use Dune\Core\App;

class Bootstrap
{
    public function run()
    {
        if(!defined('PATH')) {
            define('PATH', __DIR__ .'/App/');
        }
        $container = new Container();
        $app = new App();
        $app->setContainer($container);
        $app->setTestingMode(true);
    }
}
