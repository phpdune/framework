<?php

declare(strict_types=1);

namespace Dune\Csrf;

interface CsrfInterface
{
    /**
     * set the csrf token
     *
     * @return null|string
     */
    public function generate(): ?string;
    /**
     * regenerate csrf token
     *
     * @return null|string
     */
    public function reGenerate(): ?string;
}
