<?php

declare(strict_types=1);

namespace Dune;

use Dotenv\Dotenv;
use DI\Container;

final class App
{
    /**
     * php-di Container
     *
     * @var Container
     */
    private static Container $container;

    /**
     * load the env variables and set custom error handling
     *
     */
    public function __construct()
    {
        set_error_handler('errorHandler', E_ALL);
        set_exception_handler('exceptionHandler');
    }
       /**
        * load apo configuration
        *
        */
    public function load(): void
    {
        $this->loadEnv();
        $this->loadAppConfig();
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
}
