<?php

declare(strict_types=1);

namespace Dune\Views;

trait ViewTrait
{
    protected function initView()
    {
        return new \Dune\Views\PineCompiler();
    }
}
