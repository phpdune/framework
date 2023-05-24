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

namespace Dune\Pine;

use Dune\Pine\ViewInterface;
use Dune\Pine\ViewContainer;
use Dune\Pine\FileMapper;
use Dune\Pine\Engine\ProcceserEngine;

class View implements ViewInterface
{
    use ViewContainer;

    /**
     * The view file.
     *
     * @var string
     */
    private string $file;

    /**
     * store the data passing by the view.
     *
     * @var array<string,mixed>
     */
    private array $var = [];

    /**
     * \Dune\Pine\Engine\ProcceserEngine instance
     *
     * @var ProcceserEngine
     */
    private ?ProcceserEngine $engine = null;
    /**
     * \Dune\Pine\FileMapper instance
     *
     * @var FileMapper
     */
    public FileMapper $mapper;

    /**
     * FileMapper $mapper
     * CaptureLayout $capture
     * ProcceserEngine $engine
     * 
     * instance setting
     */
    public function __construct(FileMapper $mapper, ProcceserEngine $engine)
    {
        $this->mapper = $mapper;
        $this->engine = $engine;
    }

    /**
     * @param  string  $view
     * @param  array<string,mixed>  $data
     *
     * @throw \Dune\Pine\Exception\ViewNotFound
     *
     * @return string|null|bool
     */
    public function render(string $view, array $data = []): string|null|bool
    {
        $this->file = $this->mapper->getPineFile($view);
        $this->var = $data;
        if($this->mapper->cacheMode()) {
            return $this->engine->load($this->file, $data);
        }
        return $this->loadFile();
    }
    /**
     * compile the layout file if exists
     *
     * @return mixed
     */
    private function loadFile(): mixed
    {
        $template = $this->mapper->getContents($this->file);
        return $this->renderFiles($template);
    }
    /**
     * render the layout and view files
     *
     * @param string $template
     *
     * @return mixed
     */
    private function renderFiles(string $template): mixed
    {
        return $this->engine->load($template, $this->var);
    }
    /**
     * get cached template contents from its key
     *
     * @param string $key
     *
     * @return string
     */
    public function getCacheContent(string $key): string
    {
        $file = $this->mapper->getCacheFile($key);
        return $this->mapper->getContents($file);
    }
}
