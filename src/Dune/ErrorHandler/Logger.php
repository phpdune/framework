<?php

declare(strict_types=1);

namespace Dune\ErrorHandler;

class Logger
{
    /**
     * error log file setting
     *
     * @param none
     *
     * @return none
     */
    public function __construct()
    {
        ini_set("log_errors", 'On');
        ini_set('error_log', $this->logFile());
    }
     /**
      * will log the message
      *
      * @param string $message
      *
      * @return none
      */
    public function put(string $message): void
    {
        error_log($message);
    }
     /**
      * return app.log path
      *
      * @param none
      *
      * @return string
      */
    public function logFile(): string
    {
        return PATH.'/storage/log/app.log';
    }
}
