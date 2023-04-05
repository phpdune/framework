<?php

declare(strict_types=1);

namespace Dune\Command;

interface CommandInterface
{
    /**
     * Check the commands and pass to the corresponding method
     * 
     * @param ?string $command
     * @param ?string $arg
     *
     * @return ?string
     */
    public function request(?string $command, ?string $arg): ?string;
}
