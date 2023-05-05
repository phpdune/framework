<?php

declare(strict_types=1);

namespace Dune\Pine\Engine;

use Dune\Pine\Engine\EngineInterface;
use Dune\Pine\PineCompiler;
use Dune\Pine\FileMapper;
use Dune\Pine\CacheManager\CacheController;

abstract class AbstractEngine implements EngineInterface
{
    /**
     * Dune\Pine\PineCompiler instance
     *
     * @var PineCompiler
     */
    protected PineCompiler $compiler;

    /**
     * Dune\Pine\FileMapper instance
     *
     * @var FileMapper
     */
    protected FileMapper $mapper;
    /**
     * Dune\Pine\CacheManager\CacheController instance
     *
     * @var CacheController
     */
    protected CacheController $cache;
    /**
     * PineCompiler
     * FileMapper
     * CacheController
     * instance setting
     */
    public function __construct(PineCompiler $compiler, FileMapper $mapper, CacheController $cache)
    {
        $this->compiler = $compiler;
        $this->mapper = $mapper;
        $this->cache = $cache;
    }
    /**
     * converting all pine syntax to php
     *
     * @param  string  $view
     *
     * @return string
     */
    public function compile(string $view)
    {
        if($this->mapper->cacheMode()) {
            $template = $this->mapper->getContents($view);
            $template = $this->compiler->compile($template);
            $this->cache->put($view, $template);
            return $template;
        }
        $template = $this->compiler->compile($view);
        return $template;
    }
}
