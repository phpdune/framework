<?php

declare(strict_types=1);

namespace Dune\Session;

interface SessionInterface
{
    /**
     * will set the session with given name and value
     * @param string $key
     * @param string|array<mixed> $value
     *
     */
    public static function set(string $key, string|array $value): void;
    /**
     * get the session by given $key
     *
     * @param  string  $key
     *
     * @return null|string|array<mixed>
     */
    public static function get(string $key): string|array|null;
    /**
     * check session exist or not by key
     *
     * @param  string  $key
     *
     */
    public static function has(string $key): bool;
    /**
     * delete a specific session by given name
     *
     * @param  string  $key
     *
     */
    public static function unset(string $key): void;
    /**
     * delete all sessions
     *
     */
    public static function flush(): void;
    /**
     * will return the id of the session
     *
     *
     * @return string|int
     */
    public static function id(): string|int;
    /**
     * will return the name of the session
     *
     *
     * @return null|string
     */
    public static function name(): ?string;
    /**
     * will return session global variable values
     *
     *
     * @return array<mixed>|null
     */
    public static function all(): ?array;
    /**
    * will add new value to the current session
    *
    * @param string $key
    * @param string $value
    *
    */
    public static function overwrite(string $key, string $value): void;
}
