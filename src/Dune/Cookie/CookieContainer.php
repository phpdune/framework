<?php

declare(strict_types=1);

namespace Dune\Cookie;

use Dune\Core\App;
use Illuminate\Container\Container;

trait CookieContainer
{
    /**
     * \DI\Container instance
     *
     * @var ?Container
     */
    protected ?Container $container = null;
    /**
     * setting up the container instance
     */
    public function __setUp()
    {
        if(!$this->container) {
            if(class_exists(App::class)) {
                $container = App::container();
            } else {
                $container = new Container;
            }
            $this->container = $container;
        }
    }
}
