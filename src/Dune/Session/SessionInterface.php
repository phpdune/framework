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
    public static function get(string $key): ?string;
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
}
