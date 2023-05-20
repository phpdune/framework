<?php

declare(strict_types=1);

namespace Dune\Session\Config;

class SessionConfig
{
    /**
     * Session configuration
     *
     * @var array
     */
    private array $config;
    /**
     * set the session configuration
     * 
     * @param array $configs<mixed>
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
     * @return bool|string|null
     */
    public function get(string $key): bool|string|null
    {
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }
        if (function_exists("config")) {
            return config("session." . $key);
        }
        throw new \Exception("cannot retrieve config");
    }
}
