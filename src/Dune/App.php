<?php

declare(strict_types=1);

namespace Dune;

use Dotenv\Dotenv;
use DI\Container;
use Dune\Console\ConsoleInterface;

final class App
{
    /**
     * Dune Framework Version
     *
     * @var const
     */
    public const VERSION = '1.1.7';
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
     * load the env variables and app configs
     *
     */
    public function __construct()
    {
        $this->loadEnv();
        $this->loadAppConfig();
    }
       /**
        * load app configuration
        * set custom error handlers
        */
    public function load(): void
    {
        set_error_handler('errorHandler', E_ALL);
        set_exception_handler('exceptionHandler');
        require PATH.'/routes/web.php';
        echo runRoutes();
    }
       /**
        * load apo configuration
        *
        */
    public function loadAppConfig(): void
    {
        date_default_timezone_set(config('app.timezone'));
    }
    /**
     * env loading
     */
    public function loadEnv(): void
    {
        if(class_exists(Dotenv::class)) {
            $env = Dotenv::createImmutable(PATH);
            $env->load();
        }
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
     *
     */
    public function loadConsole(): int
    {
        return $this->console->run();
    }
}
