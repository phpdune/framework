<?php

declare(strict_types=1);

namespace Dune\Views;

use Dune\Exception\NotFound;

class View extends Parser implements ViewInterface
{
    /**
     * The view file.
     *
     * @var string
     */
    private static string $file;

    /**
     * store the data passing by the view.
     *
     * @var array
     */

    private static array $var;
    /**
     * Creating an instance of Controller
     *
     * @return none
     */
    public function __construct()
    {
        $this->controller = new Controller();
    }
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
        $viewFile = $view . ".view.php";
        self::$file = PATH . "/app/views/" . $viewFile;
        self::$var = $data;

        if (file_exists(self::$file)) {
            return self::loadFile();
        }
        throw new NotFound("Exception : {$viewFile} File Not Found In Views Directory");
    }
    /**
     * Load the view file by if it has value or not
     *
     * @param  none
     *
     * @return none
     */
    private static function loadFile(): void
    {
        $template = file_get_contents(self::$file);
        $template = self::parse($template);
        if (empty(self::$var)) {
            ob_start();
            eval(" ?>" . $template . "<?php ");
            echo ob_get_clean();
        } else {
            foreach (self::$var as $key => $value) {
                $$key = $value;
            }
            ob_start();
            eval(" ?>" . $template . "<?php ");
            echo ob_get_clean();
        }
    }
}
