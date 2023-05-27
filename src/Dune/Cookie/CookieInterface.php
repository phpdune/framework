<?php

declare(strict_types=1);

namespace Dune\Cookie;

interface CookieInterface
{
    /**
     * set the cookie
     *
     * @param string $key
     * @param string $value
     *
     */
    public function set(string $key, string $value): void;
    /**
     * get the cookie
     *
     * @param string $key
     *
     * @return string|null
     */
    public function get(string $key): mixed;
    /**
     * unset a cookie
     *
     * @param string $key
     *
     */
    public function unset(string $key): void;
    /**
     * check if the cookie exist by its key
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool;
    /**
     * delete all cookies that are currently active
     *
     */
    public function flush(): void;
    /**
     * show all the cookies that are currently active
     *
     * @return null|array<string,string>
     */
    public function all(): ?array;
}
