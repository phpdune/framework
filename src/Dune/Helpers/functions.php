<?php

declare(strict_types=1);

use Dune\Views\View;
use Dune\Routing\Router as Route;
use Dune\Exception\Errors\Error;
use Dune\Http\Request;

/**
 * view() function, to render the view from controller and to pass data to view via array
 *
 * @param  string $view
 * @param array|null $data
 *
 * @throw \Exception
 *
 * @return View|Error
 */
function view(string $view, array $data = null)
{
    try {
        if (!empty($data)) {
            return  View::render($view, $data);
        } else {
            return View::render($view);
        }
    } catch (\Exception $e) {
        return Error::handle($e->getMessage());
    }
}
/**
 * runRoutes will run all the routes from public folder
 *
 * @param  none
 *
 * @throw \Exception
 *
 * @return Route|Error
 */
function runRoutes()
{
    try {
        $request = new Request();
        $method = $request->get('_method') ?? $request->method();
        return Route::run($request->server('request_uri'), $method);
    } catch (\Exception $e) {
        return Error::handle($e->getMessage());
    }
}
/**
 * abort function, will abort and load a error file from view/errors
 *
 * @param int @code
 *
 *
 * @return none
 */
function abort(int $code = 404): void
{
    $file = PATH . '/app/views/errors/error.php';
    if (file_exists($file)) {
        http_response_code($code);
        require_once $file;
    }
}
/**
 * route function will return the route path by its name
 *
 * @param  string $key
 *
 *
 * @return Route|Error
 */
function route(string $key)
{
    $array = Route::$names;
    if (array_key_exists($key, $array)) {
        return Route::$names[$key];
    } else {
        return Error::handle("Route Not Found With Name {$key}");
    }
}
/**
 * asset function will return the file path from public/asset dir
 *
 * @param  string $file
 *
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
/**
 * dd function will die and dump
 *
 * @param mixed $data.
 *
 * @return none
 */
function dd($data)
{
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
    die();
}
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
/**
 * app custom error handler
 *
 * @param string $errno
 * @param string $errstr
 * @param string @$errfile
 * @param string @$errline
 *
 * @return Error
 */
function errorHandler($errno, $errstr, $errfile, $errline)
{
    return Error::handle($errstr, $errfile, $errline);
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
    $path = PATH.'/config/';
    $content = explode('.', $string);
    $file = $content[0].'.php';
    $key = $content[1];
    if (file_exists($path.$file)) {
        $data = include $path.$file;
        return $data[$key];
    }
    return null;
}
/**
 * generate a csrf/xsrf token
 *
 * @param none
 *
 * @return null|string
 */
function csrf(): ?string
{
    $csrfToken = Csrf::generate();
    $csrfField = '<input type="hidden" id="_token" name="_token" value="'.Session::get('_token').'">';
    return $csrfField;
}
