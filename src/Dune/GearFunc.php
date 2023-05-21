<?php

declare(strict_types=1);

use Dune\Pine\ViewLoader;
use Dune\Routing\RouteLoader;
use Dune\Routing\Router;
use Dune\ErrorHandler\Error;
use Dune\Http\Request;
use Dune\Facades\Session;
use Dune\Facades\Csrf;
use Dune\Http\Response;
use Dune\Http\Redirect;
use Dune\ErrorHandler\Logger;
use Dune\Support\Twig;
use Dune\App;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

/**
 * view() function, to render the view from controller and to pass data to view via array
 *
 * @param  string $view
 * @param array<string,mixed> $data
 *
 * @throw \Exception
 *
 * @return null|bool
 */
function view(string $view, array $data = []): ?bool
{
    $pine = new ViewLoader(
        config("pine.pine_path"),
        config("pine.cache"),
        config("pine.cache_path")
    );
    $pine = $pine->load();
    try {
        echo $pine->render($view, $data);
    } catch (\Exception $e) {
        Error::handleException($e);
    }
    return null;
}

/**
 * abort function, will abort and load a error file from view/errors
 *
 * @param int $code
 *
 * @return ?bool
 */
function abort(int $code = 404, string $message = null): ?bool
{
    $message = BaseResponse::$statusTexts[$code] ?? $message;
    $file = PATH . "/app/views/errors/error.pine.php";
    if (file_exists($file)) {
        return view("errors/error", [
            "code" => $code,
            "message" => $message,
        ]);
    }
    return null;
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
    $array = Router::$names;
    if (array_key_exists($key, $array)) {
        $route = Router::$names[$key];
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
    $csrfToken = Csrf::generate();
    $csrfField =
        '<input type="hidden" id="_token" name="_token" value="' .
        Session::get("_token") .
        '">';
    return $csrfField;
}
/**
 * return Response
 *
 * @return Response
 */
function response(): Response
{
    $container = App::container();
    return $container->get(Response::class);
}
/**
 * return Redirect
 *
 * @return Redirect
 */

function redirect(): Redirect
{
    return new Redirect(new RedirectResponse("/fake"));
}
/**
 * for logging message
 *
 * @param mixed $message
 *
 */
function logs(mixed $message): void
{
    $logger = new Logger();
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
    return Session::get($key);
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
    return Session::has($key) ? true : false;
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
    return Session::get("old_" . $key);
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
    if (class_exists(Twig::class)) {
        $twig = new Twig($path, $config);
        echo $twig->render($file, $data);
    }
}
