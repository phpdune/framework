<?php

declare(strict_types=1);

namespace Dune\ErrorHandler;

use Dune\ErrorHandler\Logger;

class Error
{
    /**
     * will handle the custom error message
     *
     * @param string $message
     * @param string|null $file
     * @param string|null @line
     *
     * @return none
     */
    public static function handle(string $message, string $file = '', $line = ''): void
    {
        $code = str_contains($message, 'Not Found') ? 404 : 500;
        if ($file && $line) {
            $message = $message. ' in '.$file.' on line '.$line;
        }

        if (self::debugMode()) {
            require_once self::getErrorPage();
            exit();
        }
        self::logMessage($message);
        abort($code);
        exit();
    }
    /**
     * will handle the custom exception
     *
     * @param \Exception $e
     *
     * @return none
     */
    public static function handleException(\Exception $e): void
    {
        $code = str_contains($e->getMessage(), 'Not Found') ? 404 : 500;
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
     * @param none
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
     * @param none
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
     * @return none
     */
    private static function logMessage(string $message): void
    {
        $logger = new Logger();
        $logger->put($message);
    }
}
