<?php

declare(strict_types=1);

namespace Dune\Pine;

use Dune\Pine\Exception\ViewNotFound;
use Dune\Pine\Exception\LayoutNotFound;

class FileMapper
{
    /**
     * cache mode
     *
     * @var bool
     */
    protected bool $cacheMode = false;
    /**
     * compiled view cache file path
     *
     * @var string
     */
    protected ?string $cacheFilePath = null;
    /**
     * pine file path
     *
     * @var string
     */
    protected string $pineFilePath;
    /**
     * cache file extension
     *
     * @var string
     */
    protected string $cacheExtension = '.php';
    /**
     * pine file extensions
     *
     * @var string
     */
    protected string $pineExtension = '.pine.php';
    /**
     * cache file path setting
     */
    public function __construct(string $pinePath, ?string $cachePath = null, bool $cacheMode = false)
    {
        $this->pineFilePath = $pinePath;
        $this->cacheFilePath = $cachePath;
        $this->cacheMode = $cacheMode;
    }
    /**
     * getting cache file by $key
     *
     * @param string $key
     *
     * @return string|bool
     */
    public function getCacheFile(string $key): string|bool
    {
        $file = md5($key);
        $file = substr($file, 0, 20);
        $file = $file.$this->cacheExtension;
        if(file_exists($this->cacheFilePath .DIRECTORY_SEPARATOR. $file)) {
            return $this->cacheFilePath .DIRECTORY_SEPARATOR. $file;
        }
        return false;
    }
    /**
     * get the cached file template
     *
     * @param string $file
     *
     * @return string
     */
    public function getContents(string $file): string
    {
        return file_get_contents($file);
    }
    /**
     * get the pine file path by file name
     *
     * @throw \Dune\Pine\Exception\ViewNotFound
     *
     * @param string $file
     *
     * @return string
     */
     public function getPineFile(string $file): string
     {
         $file = $this->pineFilePath.DIRECTORY_SEPARATOR.$file .$this->pineExtension;
         if(file_exists($file)) {
             return $file;
         }
         throw new ViewNotFound(
             "Exception : {$file} File Not Found In Views Directory"
         ,404);
     }
    /**
     * get the layout file path by file name
     *
     * @throw \Dune\Pine\LayoutNotFound
     *
     * @param string $file
     *
     * @return string
     */
     public function getLayoutFile(string $file): string
     {
         $file = $this->pineFilePath.DIRECTORY_SEPARATOR. 'layouts' .DIRECTORY_SEPARATOR.$file .$this->pineExtension;
         if(file_exists($file)) {
             return $file;
         }
         throw new LayoutNotFound(
             "Exception : {$file} File Not Found In views/layouts Directory"
         ,404);
     }
     /**
     * get cache file extension
     *
     * @return string
     */
    public function getCacheExtension(): string
    {
        return $this->cacheExtension;
    }
     /**
     * get pine file extension
     *
     * @return string
     */
    public function getPineExtension(): string
    {
        return $this->pineExtension;
    }
     /**
     * get cache file path
     *
     * @return ?string
     */
    public function getCacheFilePath(): ?string
    {
        return $this->cacheFilePath;
    }
     /**
     * get pine file path
     *
     * @return string
     */
    public function getPineFilePath(): string
    {
        return $this->pineFilePath;
    }
     /**
     * get cacheMode
     *
     * @return bool
     */
    public function cacheMode(): bool
    {
        return $this->cacheMode;
    }
}
