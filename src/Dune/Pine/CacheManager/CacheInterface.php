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

namespace Dune\Pine\CacheManager;

interface CacheInterface
{
    /**
     * cache putting
     * putting compiled template to cache folder
     *
     * @param string $key
     * @param string $compiledTemplate
     *
     * @return bool
     */
    public function put(string $key, string $compiledTemplate): bool;
    /**
     * clearing all cache files
     *
     * @return bool
     */
    public function clearAll(): bool;
    /**
     * clear single cache file by its key
     *
     * @param string $key
     *
     * @return bool
     */
    public function clear(string $key): bool;
    /**
     * check cached file exists or not by key
     *
     * @param string $key
     *
     * @return bool
     */
    public function isCached(string $key): bool;
}
