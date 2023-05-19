<?php

declare(strict_types=1);

namespace Dune\Pine;

use Dune\Pine\View;
use Dune\Pine\CacheManager\CacheController;
use Dune\Pine\FileMapper;
use Dune\Pine\CaptureLayout;
use Dune\Pine\Engine\ProcceserEngine;
use Dune\Pine\Parser\Parser;
use Dune\Pine\Parser\PhpParser;
use Dune\Pine\Parser\PineParser;

class ViewLoader
{
    /**
     * \Dune\Views\View instance
     *
     * @var View
     */
    private ?View $pine = null;
    /**
     * view configuration setting
     *
     * @param string $path
     * @param bool $cacheMode
     * @param ?string $cachePath
     */
    public function __construct(string $path, bool $cacheMode = false, ?string $cachePath = null)
    {
        $mappper = new FileMapper($path, $cachePath, $cacheMode);
        $this->pine = new View(
            $mappper,
            new ProcceserEngine(
                new PineCompiler(
                    new CaptureLayout($mappper)
                ),
                $mappper,
                new CacheController($mappper)
            ),
        );
    }
    /**
     * \Dune\Views\View instance returning
     *
     * @return View
     */
     public function load(): View
     {
         return $this->pine;
     }

}
