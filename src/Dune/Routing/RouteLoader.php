<?php

declare(strict_types=1);

namespace Dune\Routing;

use Dune\Routing\RouterContainer;
use Dune\Routing\Router;

class RouteLoader
{
    use RouterContainer;
    /**
     * \Dune\Routing\Router instance
     *
     * @var ?Router
     */
    protected ?Router $route = null;
    /**
     * calling router method
     * setting up router instance
     *
     */
    public function __construct()
    {
        $this->__setUp();
        if(!$this->route) {
            $this->route = $this->container->get(Router::class);
        }
    }
      /**
       * returning the loaded router instance
       *
       * @return Router
       */
    public function load(): Router
    {
        return $this->route;
    }
}
