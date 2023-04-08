<?php

declare(strict_types=1);

use Dune\Views\View;
use Dune\Routing\Router as Route;
use Dune\ErrorHandler\Error;
use Dune\Http\Request;
use Dune\Session\Session;
use Dune\Csrf\Csrf;
use Dune\Helpers\Response;
use Dune\Helpers\Redirect;
use Dune\ErrorHandler\Logger;

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
        return Error::handleException($e);
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
        return Error::handleException($e);
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
function abort(int $code = 404, string $message = null)
{
    $file = PATH . '/app/views/errors/error.view.php';
    if (file_exists($file)) {
        return view('errors/error', [
          'code' => $code,
          'message' => $message
          ]);
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
      
        trigger_error("Route Not Found With Name {$key}");
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
    return Error::handle($errno,$errstr, $errfile, $errline);
}
/**
 * app custom exception handler
 *
 * @param mixed $e
 *
 * @return Error
 */
function exceptionHandler($e)
{
    return Error::handleException($e);
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
/**
 * return Response
 *
 * @param none
 *
 * @return string|null
 */
function response(): Response
{
    return new Response();
}
/**
 * return Redirect
 *
 * @param none
 *
 * @return string|null
 */

function redirect(): Redirect
{
    return new Redirect();
}
/**
* for logging message
*
* @param mixed $message
*
* @return none
*/
function logs(mixed $message): void
{
    $logger = new Logger();
    $logger->put($message);
}
/**
* return current memory usage
*
* @param none
*
* @return string
*/
function memory(): string
{
  $size = memory_get_usage(true);
  $unit = array('b','kb','mb','gb','tb','pb');
return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}