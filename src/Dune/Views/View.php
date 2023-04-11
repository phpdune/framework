<?php

declare(strict_types=1);

namespace Dune\Views;

use Dune\Exception\NotFound;

class View extends Compiler implements ViewInterface
{
    /**
     * The view file.
     *
     * @var string
     */
    private static string $file;
    /**
     * The layout file.
     *
     * @var string
     */
    private static string $layout;

    /**
     * store the data passing by the view.
     *
     * @var array
     */

    private static array $var;

    /**
     * @param  string  $view
     * @param  array  $data
     *
     * @throw \NotFound
     *
     * @return string|null
     */
    public static function render(string $view, array $data = []): ?string
    {
        $viewFile = $view . ".pine.php";
        self::$file = PATH . "/app/views/" . $viewFile;
        self::$var = $data;

        if (file_exists(self::$file)) {
            return self::loadFile();
        }
        throw new NotFound(
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
    private static function loadFile(): ?string
    {
        $template = file_get_contents(self::$file);
        $template = self::compile($template);
        if (
            preg_match_all(
                "/{[\s]?extends\.(\w{1,})[\s]?}/",
                $template,
                $matches
            )
        ) {
            self::$layout =
                PATH . "/app/views/layouts/" . $matches[1][0] . ".pine.php";
            if (!file_exists(self::$layout)) {
                throw new NotFound(
                    "Exception : {$matches[1][0]}.pine.php File Not Found In views/layouts Directory"
                );
            }
            $template = self::compileExtends($template, $matches[0][0]);
            $layoutTemplate = file_get_contents(self::$layout);
            $layoutTemplate = self::compile($layoutTemplate);
        }

        return isset(self::$layout)
            ? self::renderFiles($template, $layoutTemplate)
            : self::renderFiles($template);
    }
    /**
     * render the layout and view files
     *
     * @param  none
     *
     * @return none
     */
    private static function renderFiles(
        string $template,
        string $layoutTemplate = null
    ): void {
        if (empty(self::$var) && isset(self::$layout)) {
            ob_start();
            eval(" ?>" . $template . "<?php ");
            $content = ob_get_clean();
            ob_start();
            eval(" ?>" . $layoutTemplate . "<?php ");
            echo ob_get_clean();
        } else {
            foreach (self::$var as $key => $value) {
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
