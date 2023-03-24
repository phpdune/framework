<?php

declare(strict_types=1);

namespace Dune\Session;

interface SessionInterface
{
    /**
     * will set the session with given name and value
     * @param  string  $key
     * @param  string  $value
     *
     * @return none
     */
    public static function set(string $key, string $value): void;
    /**
     * get the session by given $key
     *
     * @param  string  $value
     *
     * @return null|string
     */
    public static function get(string $key): string|array|null;
    /**
     * get all session
     *
     * @param  none
     *
     * @return array|null
     */
    public static function getAll(): ?array;
    /**
     * check session exist or not by key
     *
     * @param  string  $key
     *
     * @return none
     */
    public static function has(string $key): bool;
    /**
     * delete a specific session by given name
     *
     * @param  string  $key
     *
     * @return none
     */
    public static function unset(string $key): void;
    /**
     * delete all sessions
     *
     * @param  none
     *
     * @return none
     */
    public static function flush(): void;
    /**
     * will return the id of the session
     *
     * @param none
     *
     * @return string|int
     */
    public static function id(): string|int;
    /**
     * will return the name of the session
     *
     * @param none
     *
     * @return null|string
     */
    public static function name(): ?string;
    /**
     * will return session global variable values
     *
     * @param none
     *
     * @return array|null
     */
    public static function all(): ?array;
    /**
    * will add new value to the current session
    *
    * @param string $key
    * @param string $value
    *
    * @return mixed
    */
    public static function overwrite(string $key, string $value): void;
}
