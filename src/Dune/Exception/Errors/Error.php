<?php

declare(strict_types=1);

namespace Dune\Exception\Errors;

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

        $debuginfo = env('APP_DEBUG');
        if ($debuginfo === 'true') {
            require_once PATH.'/core/Exception/Errors/main.php';
            exit();
        }
        abort($code);
        exit();
    }
}
