<?php

declare(strict_types=1);

namespace Dune\Http;

use Dune\App;
use DI\Container;
use DI\ContainerBuilder;

trait RequestContainer
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
                $containerBuilder = new ContainerBuilder();
                $container = $containerBuilder->build();
            }
            $this->container = $container;
        }
    }
}
