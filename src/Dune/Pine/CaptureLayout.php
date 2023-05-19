<?php

declare(strict_types=1);

namespace Dune\Pine;

use Dune\Pine\FileMapper;

class CaptureLayout
{
    /**
     * \Dune\Pine\FileMapper instance
     *
     * @var FileMapper
     */
    private FileMapper $mapper;
    /**
     * layout file name
     *
     * @var ?string
     */
    private ?string $layoutName = null;
    /**
     * pine template
     *
     * @var string
     */
    private string $template;
    /**
     * layout data, including layout name its contents
     *
     * @var array<string,string>
     */
    private array $layoutData = [];
    /**
     * \Dune\Pine\FileMapper instance setting
     */
    public function __construct(FileMapper $mapper)
    {
        $this->mapper = $mapper;
    }
     /**
      * layout contents will be merged and return the file if layout is enabled
      *
      * @param string $template
      *
      * @return string
      */
    public function layout(string $template): string
    {
        $this->template = $template;
        $this->captureContents();
        return $this->getTemplate();

    }
     /**
      * capturing details from layout block
      */
    private function captureContents(): void
    {

        preg_match_all('/<p-extends\s*=\s*"([^"]+)"/', $this->template, $extends);

        preg_match_all('/<\s*p-layout\s*=\s*"([a-zA-Z_][a-zA-Z0-9_]*)"\s*>(.*?)<\/p-layout\s*=\s*"\1"\s*>/s', $this->template, $matches);

        foreach ($matches[1] as $key => $name) {
            $this->layoutData[$name] = $matches[2][$key];
        }
        if(array_key_exists(0,$extends[1])) {
        $this->layoutName = $extends[1][0];
        }
    }
     /**
      * merging the layout contents with pine file
      *
      * @return string
      */
    private function getTemplate(): string
    {
        if($this->layoutName) {
            $layout = $this->mapper->getLayoutFile($this->layoutName);
            $layout = $this->mapper->getContents($layout);
            foreach ($this->layoutData as $key => $value) {
                $replace = "<p-yield=\"$key\">";
                $layout = str_replace($replace, $value, $layout);
            }
            return $layout;
        }
        return $this->template;
    }
}
