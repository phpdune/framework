<?php

declare(strict_types=1);

namespace Dune\ErrorHandler;

use Dune\ErrorHandler\Logger;

class Error
{
    public function __construct()
    {
        register_shutdown_function(function () {
            $error = error_get_last();
            if ($error) {
                throw new \Exception($error['message'], 500);
            }
        });
    }
    /**
     * will handle the custom error message
     *
     * @param string|int|null $errno
     * @param string|null $errstr
     * @param string|null $errfile
     * @param string|int|null $errline
     *
     * @throw \Exception
     */
    public static function handle(int|null|string $errno, ?string $errstr, ?string $errfile, int|null|string $errline): void
    {
        if (error_reporting() && $errno) {
            return;
        }
        throw new \Exception($errstr, 500);
    }
    /**
     * will handle the custom exception
     *
     * @param mixed $e
     *
     */
    public static function handleException($e): void
    {
        $code = $e->getCode() ?? 500;
        $message = $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();

        if (self::debugMode()) {
            require_once self::getErrorPage();
            exit();
        }
        self::logMessage($message);
        abort($code);
        exit();
    }
    /**
     * return error page path
     *
     * @return null|string
     */
    private static function getErrorPage(): ?string
    {
        if (file_exists(PATH.'/vendor/dune/framework/src/Dune/ErrorHandler/template.php')) {
            return PATH.'/vendor/dune/framework/src/Dune/ErrorHandler/template.php';
        }
        return null;
    }
    /**
     * check if the app enabled debug mode
     *
     * @return bool
     */
    private static function debugMode(): bool
    {
        if (env('APP_DEBUG') === 'true') {
            return true;
        }
        return false;
    }
    /**
     * will log message
     *
     * @param string $message
     *
     */
    private static function logMessage(string $message): void
    {
        $logger = new Logger();
        $logger->put($message);
    }
}
