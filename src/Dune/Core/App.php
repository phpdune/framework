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
     * @var const
     */
    public const VERSION = '1.1.9';
    /**
     * \Di\Container instance
     *
     * @var Container
     */
    private static Container $container;
    /**
     * \Dune\Console\ConsoleInterface
     *
     * @var ConsoleInterface
     */
    private ConsoleInterface $console;
    /**
     * app is in testing or not
     *
     * @var bool
     */
    private bool $testing = false;
    /**
     * load the env variables and app configs
     *
     */
    public function __construct()
    {
        $this->loadAppConfig();
        $this->loadEnv();
        $this->loadEloquent();
    }

       /**
        * load app configuration
        *
        */
    public function loadAppConfig(): void
    {
        date_default_timezone_set(config('app.timezone'));
    }

    /**
     * php-di container setter
     *
     * @param Container $container
     *
     */
    public function setContainer(Container $container): void
    {
        self::$container = $container;
    }
    /**
     * php-di container getter
     *
     * @return Container
     */
    public static function container(): Container
    {
        return self::$container;
    }
    /**
     * Initializing ConsoleInterface
     *
     * @param ConsoleInterface $console
     */
    public function console(ConsoleInterface $console): void
    {
        $this->console = $console;
    }
    /**
     * running the console
     */
    public function loadConsole(): int
    {
        $this->loadEloquent();
        return $this->console->run();
    }
    /**
     * check the app is local
     * getting APP_ENV from .env file
     *
     * @return bool
     */
    public function isLocal(): bool
    {
        return (env('APP_ENV') == 'local' ? true : false);
    }
    /**
     * set the app testing mode
     *
     * @return void
     */
    public function setTestingMode(bool $value): void
    {
        $this->testing = $value;
    }
    /**
     * return app is on testing mode or not
     *
     * @return bool
     */
    public function isTesting(): bool
    {
        return $this->testing;
    }
    /**
     * checks the app is in maintenance mode or not
     */
    public function isMaintenance()
    {
        //
    }
    /**
     * boot up the eloquent orm
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
