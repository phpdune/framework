<?php

declare(strict_types=1);

namespace Dune\ErrorHandler;

class Error
{
    /**
     * will handle the custom errors
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

        if (env('APP_DEBUG') === 'true') {
            require_once PATH.'/vendor/dune/framework/src/Dune/ErrorHandler/main.php';
            exit();
        }
        abort($code);
        exit();
    }
}
