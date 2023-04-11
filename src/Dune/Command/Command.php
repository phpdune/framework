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
          } elseif($command == "create:middleware") {
            return $this->createMiddleware($arg);
          } elseif($command == "create:request") {
            return $this->createRequest($arg);
          }
        return 'Invalid Command'.PHP_EOL;
    }
}
