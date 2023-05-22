<?php

declare(strict_types=1);

namespace Dune;

use Dotenv\Dotenv;
use DI\Container;
use Dune\Console\ConsoleInterface;
use Dune\Database\EloquentBooter;

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
        $env = Dotenv::createImmutable(PATH);
        $env->load();
    }
       /**
        * load app configuration
        * set custom error handlers
        */
    public function load(): void
    {
        $this->loadAppConfig();
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
    public function loadEloquent()
    {
      $eloquent = new EloquentBooter();
      $eloquent->boot();
    }
}
