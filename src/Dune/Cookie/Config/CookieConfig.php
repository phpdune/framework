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

namespace Dune\Cookie\Config;

class CookieConfig
{
    /**
     * Cookie configuration
     *
     * @var array<string, string|bool>
     */
    private array $config;
    /**
     * set the session configuration
     *
     * @param array<string, string|bool> $configs
     */
    public function __construct(array $configs = [])
    {
        $this->config = $configs;
    }
    /**
     * get the session configuration
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key): mixed
    {
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }
        if (function_exists("config")) {
            return config("cookie." . $key);
        }
        throw new \Exception("Cannot retrieve cookie config", 500);
    }
}
