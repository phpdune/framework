<?php

declare(strict_types=1);

namespace Dune\Views;

use Dune\Views\Exception\ViewNotFound;
use Dune\Views\Exception\LayoutNotFound;

class View implements ViewInterface
{
    use ViewTrait;

    /**
     * The view file.
     *
     * @var string
     */
    private string $file;
    /**
     * The layout file.
     *
     * @var string
     */
    private string $layout;

    /**
     * store the data passing by the view.
     *
     * @var array
     */

    private array $var;
    /**
     * \Dune\Views\PineCompiler instance
     *
     * @var PineCompiler
     */
    private ?PineCompiler $pine = null;

    /**
     * @param none
     *
     * @return none
     */
    public function __construct()
    {
        if (is_null($this->pine)) {
            $this->pine = $this->initView();
        }
    }

    /**
     * @param  string  $view
     * @param  array  $data
     *
     * @throw \Dune\Views\Exception\ViewNotFound
     *
     * @return string|null
     */
    public function render(string $view, array $data = []): ?string
    {
        $viewFile = $view . ".pine.php";
        $this->file = PATH . "/app/views/" . $viewFile;
        $this->var = $data;

        if (file_exists($this->file)) {
            return $this->loadFile();
        }
        throw new ViewNotFound(
            "Exception : {$viewFile} File Not Found In Views Directory"
        );
    }
    /**
     * compile the layout file if exists
     *
     * @param  none
     *
     * @return none
     */
    private function loadFile(): ?string
    {
        $template = file_get_contents($this->file);
        $template = $this->pine->compile($template);
        if (
            preg_match_all(
                "/{[\s]?extends\.(\w{1,})[\s]?}/",
                $template,
                $matches
            )
        ) {
            $this->layout =
                PATH . "/app/views/layouts/" . $matches[1][0] . ".pine.php";
            if (!file_exists($this->layout)) {
                throw new LayoutNotFound(
                    "Exception : {$matches[1][0]}.pine.php File Not Found In views/layouts Directory"
                );
            }
            $template = $this->pine->compileExtends($template, $matches[0][0]);
            $layoutTemplate = file_get_contents($this->layout);
            $layoutTemplate = $this->pine->compile($layoutTemplate);
        }

        return isset($this->layout)
            ? $this->renderFiles($template, $layoutTemplate)
            : $this->renderFiles($template);
    }
    /**
     * render the layout and view files
     *
     * @param  none
     *
     * @return none
     */
    private function renderFiles(
        string $template,
        string $layoutTemplate = null
    ): void {
        if (empty($this->var) && isset($this->layout)) {
            ob_start();
            eval(" ?>" . $template . "<?php ");
            $content = ob_get_clean();
            ob_start();
            eval(" ?>" . $layoutTemplate . "<?php ");
            echo ob_get_clean();
        } else {
            foreach ($this->var as $key => $value) {
                $$key = $value;
            }
            if ($layoutTemplate) {
                ob_start();
                eval(" ?>" . $template . "<?php ");
                $content = ob_get_clean();
                ob_start();
                eval(" ?>" . $layoutTemplate . "<?php ");
                echo ob_get_clean();
            } else {
                ob_start();
                eval(" ?>" . $template . "<?php ");
                echo ob_get_clean();
            }
        }
    }
}
