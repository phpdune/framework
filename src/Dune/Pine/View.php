= $mapper;
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
