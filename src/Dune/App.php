<?php

declare(strict_types=1);

namespace Dune;

use Dotenv\Dotenv;

final class App
{
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
}
