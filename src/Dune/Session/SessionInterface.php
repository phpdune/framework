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

namespace Dune\Session;

interface SessionInterface
{
    /**
     * will set the session with given name and value
     * @param string $key
     * @param string|array<mixed> $value
     *
     * @return mixed
     */
    public function set(string $key, string|array $value): mixed;
    /**
     * get the session by given $key
     *
     * @param  string  $key
     *
     * @return mixed
     */
    public function get(string $key): mixed;
    /**
     * check session exist or not by key
     *
     * @param  string  $key
     *
     */
    public function has(string $key): bool;
    /**
     * delete a specific session by given name
     *
     * @param  string  $key
     *
     */
    public function unset(string $key): void;
    /**
     * delete all sessions
     *
     */
    public function flush(): void;
    /**
     * will return the id of the session
     *
     *
     * @return string|int
     */
    public function id(): string|int;
    /**
     * will return the name of the session
     *
     *
     * @return null|string
     */
    public function name(): ?string;
    /**
     * will return session global variable values
     *
     *
     * @return array<mixed>|null
     */
    public function all(): ?array;
    /**
    * will add new value to the current session
    *
    * @param string $key
    * @param string $value
    *
    */
    public function overwrite(string $key, string $value): void;
}
