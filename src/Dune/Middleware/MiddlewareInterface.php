<?php

declare(strict_types=1);

namespace Dune\Middleware;

interface MiddlewareInterface
{
    /**
     * array of rules and values to validate the input requests
     * @return null|array<mixed>
     */
    public function handle(): ?array;
}
