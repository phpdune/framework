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

/**
 * view() function, to render the view from controller and to pass data to view via array
 *
 * @param  string $view
 * @param array<string,mixed> $data
 *
 * @throw \Exception
 *
 * @return null
 */
function view(string $view, array $data = []): null
{
    $pine = (new \Dune\Pine\ViewLoader(
        config("pine.pine_path"),
        config("pine.cache"),
        config("pine.cache_path")
    ))->load();
    try {
        echo $pine->render($view, $data);
    } catch (\Exception $e) {
        \Dune\ErrorHandler\Error::handleException($e);
    }
    return null;
}

/**
 * abort function, will abort and load a error file from view/errors
 *
 * @param int $code
 *
 * @throw \Exception
 *
 * @return null
 */
function abort(int $code = 404, string $message = null): null
{
    $message = \Dune\Http\Response::$statusTexts[$code] ?? $message;
    $file = PATH . "/app/views/errors/error.pine.php";
    if (file_exists($file)) {
        return view("errors/error", [
            "code" => $code,
            "message" => $message,
        ]);
    }
    throw new \Exception("error.pine.php not found");
}
/**
 * route function will return the route path by its name
 *
 * @param  string $key
 * @param array<mixed> $values
 *
 * @return ?string
 */
function route(string $key, array $values = []): ?string
{
    $array = \Dune\Routing\Router::$names;
    if (array_key_exists($key, $array)) {
        $route = \Dune\Routing\Router::$names[$key];
        if (str_contains($route, "{") && str_contains($route, "}")) {
            $route = str_replace(
                array_keys($values),
                array_values($values),
                $route
            );
            $route = str_replace("{", "", $route);
            $route = str_replace("}", "", $route);
            return $route;
        }
        return $route;
    }
    return null;
}
/**
 * asset function will return the file path from public/asset dir
 *
 * @param  string $file
 *
 * @return string
 */
function asset(string $file)
{
    return "/asset/" . $file;
}
/**
 * method function help to set, custom http request like PUT/PATCH/DELETE in view file
 *
 * @param string $method
 *
 * @return string
 */
function method(string $method)
{
    return '<input type="hidden" name="_method" value="' . $method . '"/>';
}
if (!function_exists("env")) {
    /**
     * env function will return the value from .env file
     *
     * @param string $key.
     *
     * @return string|null
     */
    function env($key)
    {
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }
        return null;
    }
}

/**
 * return config values
 *
 * @param string $string
 *
 * @return null|string
 */
function config(string $string): mixed
{
    $path = PATH . "/config/";
    $content = explode(".", $string);
    $file = $content[0] . ".php";
    if (file_exists($path . $file)) {
        $data = include $path . $file;
        if (str_contains($string, ".")) {
            return $data[$content[1]];
        }
        return $data;
    }
    return null;
}
/**
 * generate a csrf/xsrf token
 *
 * @return string
 */
function csrf(): string
{
    $csrfToken = \Dune\Facades\Csrf::generate();
    $csrfField =
        '<input type="hidden" id="_token" name="_token" value="' .
        \Dune\Facades\Session::get("_token") .
        '">';
    return $csrfField;
}

/**
 * return Redirect
 *
 * @return Redirect
 */

function redirect(): Redirect
{
    return new \Dune\Http\Redirect(new RedirectResponse("/fake"));
}
/**
 * for logging message
 *
 * @param mixed $message
 *
 */
function logs(mixed $message): void
{
    $logger = new \Dune\Error\Logger();
    $logger->put($message);
}
/**
 * return current memory usage
 *
 * @return string
 */
function memory(): string
{
    $size = memory_get_usage(true);
    $unit = ["b", "kb", "mb", "gb", "tb", "pb"];
    return @round($size / pow(1024, $i = floor(log($size, 1024))), 2) .
        " " .
        $unit[$i];
}
/**
 * get error message of the form field by session
 *
 * @param string $key
 *
 * @return mixed
 */
function error(string $key): mixed
{
    return \Dune\Facades\Session::get($key);
}
/**
 * check error message of the form field by session
 *
 * @param string $key
 *
 * @return bool
 */
function errorHas(string $key): bool
{
    return \Dune\Facades\Session::has($key) ? true : false;
}
/**
 * return the old value of input fields of form
 *
 * @param string $key
 *
 * @return mixed
 */
function old(string $key): mixed
{
    return \Dune\Facades\Session::get("old_" . $key);
}
/**
 * return the old value of input fields of form
 *
 * @param string $file
 * @param array<string,mixed> $data
 *
 */
function twig(string $file, array $data = []): void
{
    $path = config("twig.twig_path");

    $config = [
        "debug" => config("twig.debug"),

        "cache" => config("twig.cache"),

        "auto_reload" => config("twig.auto_reload"),

        "strict_variables" => config("twig.strict_variables"),
    ];
    if (class_exists(\Dune\Support\Twig::class)) {
        $twig = new \Dune\Support\Twig($path, $config);
        echo $twig->render($file, $data);
    }
}
