<?php

declare(strict_types=1);

namespace Dune\Command;

class Command extends CommandHandler implements CommandInterface
{
    /**
     * 
     * @param ?string $command
     * @param ?string $arg
     *
     * @return ?string
     */
     
    public function request(?string $command, ?string $arg): ?string
    {
        if ($command == "create:controller") {
            return $this->createController($arg);
        }
        return 'Invalid Command'.PHP_EOL;
    }
}
