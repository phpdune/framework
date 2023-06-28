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

namespace Dune\ErrorHandler;

class Logger
{
    /**
     * error log file setting
     *
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
     */
    public function put(string $message): void
    {
        error_log($message);
    }
    /**
     * return app.log path
     *
     * @return string
     */
    public function logFile(): string
    {
        return PATH.'/log/app.log';
    }
}
