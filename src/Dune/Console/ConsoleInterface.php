<?php

declare(strict_types=1);

namespace Dune\Console;

interface ConsoleInterface
{
    /**
     * loading the configuration and console command registration
     * running
     *
     * @return int
     */
    public function run(): int;
}
