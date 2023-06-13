<?php

/*
 * This file is part of Dune Framework.
 *
 * (c) Abhishek B <phpdune@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Dune\Core;

use Symfony\Component\Dotenv\Dotenv;
use DI\Container;
use DI\ContainerBuilder;
use Dune\Console\ConsoleInterface;
use Dune\Database\EloquentBooter;
use Dune\ErrorHandler\Error;

final class App
{
    /**
     * Dune Framework Version
     *
     * @var string
     */
    public const VERSION = '1.1.9';

    /**
     * The DI container instance.
     *
     * @var Container
     */
    private static Container $container;

    /**
     * The ConsoleInterface instance.
     *
     * @var ConsoleInterface
     */
    private ConsoleInterface $console;

    /**
     * Indicates if the app is in testing mode.
     *
     * @var bool
     */
    private bool $testing = false;

    /**
     * Creates a new App instance and loads the app configuration.
     */
    public function __construct()
    {
        $this->loadAppConfig();
        $this->loadEnv();
        $this->loadEloquent();
    }

    /**
     * Loads the app configuration.
     */
    public function loadAppConfig(): void
    {
        date_default_timezone_set(config('app.timezone'));
    }

    /**
     * Sets the PHP-DI container instance.
     *
     * @param Container $container The container instance.
     */
    public function setContainer(Container $container): void
    {
        self::$container = $container;
    }

    /**
     * Gets the PHP-DI container instance.
     *
     * @return Container The container instance.
     */
    public static function container(): Container
    {
        return self::$container;
    }

    /**
     * Sets the ConsoleInterface instance.
     *
     * @param ConsoleInterface $console The ConsoleInterface instance.
     */
    public function console(ConsoleInterface $console): void
    {
        $this->console = $console;
    }

    /**
     * Runs the console application.
     *
     * @return int The exit code of the console application.
     */
    public function loadConsole(): int
    {
        $this->loadEloquent();
        return $this->console->run();
    }

    /**
     * Checks if the app is running in the local environment.
     *
     * @return bool True if the app is running in the local environment, false otherwise.
     */
    public function isLocal(): bool
    {
        return (env('APP_ENV') == 'local' ? true : false);
    }

    /**
     * Sets the testing mode for the app.
     *
     * @param bool $value The testing mode value.
     */
    public function setTestingMode(bool $value): void
    {
        $this->testing = $value;
    }

    /**
     * Checks if the app is running in testing mode.
     *
     * @return bool True if the app is running in testing mode, false otherwise.
     */
    public function isTesting(): bool
    {
        return $this->testing;
    }

    /**
     * Checks if the app is in maintenance mode.
     */
    public function isMaintenance()
    {
        // Implementation goes here
    }

    /**
     * Boots up the Eloquent ORM.
     */
    private function loadEloquent()
    {
        $eloquent = new EloquentBooter();
        $eloquent->boot();
    }
    /**
     * load app .env contents
     */
     private function loadEnv()
     {
         $env = new Dotenv();
         $env->load(PATH.'/.env');
     }
}
