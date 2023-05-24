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

use Dune\Pine\FileMapper;
use Dune\Pine\CacheManager\CacheInterface;

class CacheController implements CacheInterface
{
    /**
     * \Dune\Pine\FileMapper instance
     *
     * @var FileMapper
     */
    public FileMapper $mapper;
    /**
     * initializing \Dune\Pine\FileMapper instance
     */
    public function __construct(FileMapper $mapper)
    {
        $this->mapper = $mapper;
    }
    /**
     * cache putting
     * putting compiled template to cache folder
     *
     * @throw \Dune\Pine\Exception\RuntimeError
     *
     * @param string $key
     * @param string $compiledTemplate
     *
     * @return bool
     */
    public function put(string $key, string $compiledTemplate): bool
    {
        if (!$this->mapper->getCacheFile($key)) {
            $fileName = md5($key);
            $fileName = substr($fileName, 0, 20);
            $fileName = $this->mapper->getCacheFilePath().DIRECTORY_SEPARATOR.$fileName . $this->mapper->getCacheExtension();
            $file = @fopen($fileName, "w");
            @fwrite($file, $compiledTemplate);
            @fclose($file);
            return true;
        }
        return false;
    }
    /**
     * clearing all cache files
     *
     * @return bool
     */
    public function clearAll(): bool
    {
        $files = scandir($this->mapper->getCacheFilePath());
        if ($files) {
            foreach ($files as $file) {
                if ($file != ".gitignore") {
                    unlink(
                        $this->mapper->getCacheFilePath() .
                            DIRECTORY_SEPARATOR .
                            $file
                    );
                }
            }
            return true;
        }
        return false;
    }
    /**
     * clear single cache file by its key
     *
     * @param string $key
     *
     * @return bool
     */
    public function clear(string $key): bool
    {
        $fileName = md5($key);
        $fileName = substr($fileName, 0, 20);
        $fileName =
            $this->mapper->getCacheFilePath() . DIRECTORY_SEPARATOR . $fileName;
        if (file_exists($fileName)) {
            unlink($fileName);
            return true;
        }
        return false;
    }
    /**
     * check cached file exists or not by key
     *
     * @param string $key
     *
     * @return bool
     */
    public function isCached(string $key): bool
    {
        $fileName = md5($key);
        $fileName = substr($fileName, 0, 20);
        if(file_exists($this->mapper->getCacheFilePath().DIRECTORY_SEPARATOR.$fileName)) {
            return true;
        }
        return false;
    }
}
