<?php

/*
 * This file is part of Dune Framework.
 *
 * (c) Abhishek B <phpdune@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Dune\Http;

use Dune\Core\App;
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
