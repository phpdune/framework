<?php

declare(strict_types=1);

namespace Dune\Session\Config;

class SessionConfig
{
    private array $config;

    public function __construct(array $configs = [])
    {
        $this->config = $configs;
    }
    public function get(string $key)
    {
        if(array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }
        if(function_exists('config')) {
            return config('session.'.$key);
        }
        throw new \Exception('cannot retrieve config');
    }
}
