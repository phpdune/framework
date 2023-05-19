<?php

declare(strict_types=1);

namespace Dune\Http;

interface ValidaterInterface
{
    /**
     *
     * @return null|array<mixed>
     */
    public function validation(): ?array;
}
