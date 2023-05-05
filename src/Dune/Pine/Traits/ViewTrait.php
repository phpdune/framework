<?php

declare(strict_types=1);

namespace Dune\Pine\Traits;

trait ViewTrait
{
    protected View $view;

    public function capture(): void
    {
        $this->view = $this->pine;
    }
}
