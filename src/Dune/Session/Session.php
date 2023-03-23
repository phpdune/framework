<?php

declare(strict_types=1);

namespace Dune\Session;

class Session extends SessionHandler implements SessionInterface
{
    /**
     * @param  string  $key
     * @param  string  $value
     *
     * @return none
     */
    public static function set(string $key, string|array $value): void
    {
        (is_array($value) ? self::setArraySession($key, $value) : self::setSession($key, $value));
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
        return self::getSession($key);
    }
    /**
     * @param  string  $key
     *
     * @return array|null
     */
    public static function getAll(): ?array
    {
        return self::getAllSession();
    }
    /**
     * @param  string  $key
     *
     * @return none
     */
    public static function unset(string $key): void
    {
        self::unsetSession($key);
    }
    /**
     * @param none
     *
     * @return none
     */
    public static function flush(): void
    {
        self::flushSession();
    }
    /**
     * @param string $key
     *
     * @return bool
     */
    public static function has(string $key): bool
    {
        return self::sessionHas($key);
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
}
