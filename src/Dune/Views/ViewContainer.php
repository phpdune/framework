<?php

declare(strict_types=1);

namespace Dune\Views;

use Dune\Views\PineCompiler;
use Dune\App;

trait ViewContainer
{
    protected function init(): void
    {
        if(is_null($this->pine)) {
            $container = App::container();
            $this->pine = $container->get(PineCompiler::class);
        }
    }
}
