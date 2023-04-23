<?php

declare(strict_types=1);

namespace Dune\Session;

use Dune\Session\SessionHandler;
use Dune\Session\SessionContainer;
use Dune\Session\Exception\InvalidMethod;

class Session implements SessionInterface
{
    use SessionContainer;

    /**
     * Session pattern regex
     *
     * @var SessionHandler
     */
    private static ?SessionHandler $handler = null;
    /**
     * @param  string  $key
     * @param  string|array<mixed>  $value
     *
     */
    public static function set(string $key, string|array $value): void
    {
        self::init();
        (is_array($value) ? self::$handler->setArraySession($key, $value) : self::$handler->setSession($key, $value));
    }
    /**
     * @param  string  $key
     *
     * @return string|null|array<mixed>
     */

    public static function get(string $key): string|array|null
    {
        self::init();
        return self::$handler->getSession($key);
    }
    /**
     * @param string $key
     *
     */
    public static function unset(string $key): void
    {
        self::init();
        self::$handler->unsetSession($key);
    }
    /**
     * flush method
     */
    public static function flush(): void
    {
        self::init();
        self::$handler->flushSession();
    }
    /**
     * @param string $key
     *
     * @return bool
     */
    public static function has(string $key): bool
    {
        self::init();
        return self::$handler->sessionHas($key);
    }
     /**
     *
     * @return string|int
     */
    public static function id(): string|int
    {
        return session_id();
    }
    /**
     *
     * @return string|null
     */
    public static function name(): ?string
    {
        return session_name();
    }
    /**
     *
     * @return array<mixed>|null
     */
     public static function all(): ?array
     {
         self::init();
         return self::$handler->allSession();
     }
     /**
     * @param string $key
     * @param string $value
     *
     */
     public static function overwrite(string $key, string $value): void
     {
         self::init();
         self::$handler->sessionOverwrite($key, $value);
     }

     /**
     * @param ?string $method
     * @param ?array<int,string> $args
     *
     * @throw \Dune\Session\Exception\InvalidMethod
     *
     */
     public static function __callStatic($method, $args): void
     {
         if ($method == 'put' | $method == 'add') {
             self::set($args[0], $args[1]);
         } else {
             throw new InvalidMethod("Method not found with name {$method}");
         }
     }
}
