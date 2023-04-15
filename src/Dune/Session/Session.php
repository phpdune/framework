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
     * @param  string  $value
     *
     * @return none
     */
    public static function set(string $key, string|array $value): void
    {
        self::init();
        (is_array($value) ? self::$handler->setArraySession($key, $value) : self::$handler->setSession($key, $value));
    }
    /**
     * @param  string  $key
     * @param  string  $method
     * @param callable|string $action
     *
     * @return string|null
     */

    public static function get(string $key): string|array|null
    {
        self::init();
        return self::$handler->getSession($key);
    }
    /**
     * @param  string  $key
     *
     * @return none
     */
    public static function unset(string $key): void
    {
        self::init();
        self::$handler->unsetSession($key);
    }
    /**
     * @param none
     *
     * @return none
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
     * @param none
     *
     * @return string|int
     */
    public static function id(): string|int
    {
        return session_id();
    }
    /**
     * @param none
     *
     * @return string|null
     */
    public static function name(): ?string
    {
        return session_name();
    }
    /**
     * @param none
     *
     * @return array|null
     */
     public static function all(): ?array
     {
         return empty($_SESSION) ? null : $_SESSION;
     }
     /**
     * @param string $key
     * @param string $value
     *
     * @return mixed
     */
     public static function overwrite(string $key, string $value): void
     {
         self::init();
         self::$handler->sessionOverwrite($key, $value);
     }

     /**
     * @param ?string $method
     * @param ?array $args
     *
     * @throw \Dune\Session\Exception\InvalidMethod
     *
     * @return none
     */
     public static function __callStatic($method, $args)
     {
         if ($method == 'put') {
             self::set($args[0], $args[1]);
         } else {
             throw new InvalidMethod("Method not found with name {$method}");
         }
     }
}
