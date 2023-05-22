<?php

declare(strict_types=1);

namespace Dune\Console;

class BerryCommands
{
    protected array $commands = [
      \Dune\Console\Commands\AppTesting::class,
      \Dune\Console\Commands\ClearViewCache::class,
      \Dune\Console\Commands\CreateController::class,
      \Dune\Console\Commands\CreateMiddleware::class,
      \Dune\Database\Console\CreateMigration::class,
      \Dune\Console\Commands\CreateValidation::class,
      \Dune\Console\Commands\DevelopmentServer::class,
      \Dune\Database\Console\DropAllTable::class,
      \Dune\Database\Console\MigrateCommand::class,
      \Dune\Database\Console\MigrateFresh::class
      ];
}
