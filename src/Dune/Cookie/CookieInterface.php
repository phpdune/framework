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
    public static function set(string $key, string $value): void;
    /**
     * get the cookie
     *
     * @param string $key
     *
     * @return string|null
     */
    public static function get(string $key): mixed;
    /**
     * unset a cookie
     *
     * @param string $key
     *
     */
    public static function unset(string $key): void;
    /**
     * check if the cookie exist by its key
     *
     * @param string $key
     *
     * @return bool
     */
    public static function has(string $key): bool;
    /**
     * delete all cookies that are currently active
     *
     */
    public static function flush(): void;
    /**
     * show all the cookies that are currently active
     *
     * @return null|array<string,string>
     */
    public static function all(): ?array;
}
