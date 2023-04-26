<?php

declare(strict_types=1);

namespace Dune\Http;

use Dune\Http\Validater;

trait RequestContainer
{
    protected function init(): void
    {
        if (is_null($this->validater)) {
            $container = new \Dune\Container\Container();
            $this->validater = $container->get(Validater::class);
        }
    }
}
