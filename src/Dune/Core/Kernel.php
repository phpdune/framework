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

namespace Dune\Core;

use Dune\Core\App;
use DI\Container;
use Dune\Http\ResponseInterface;
use DI\ContainerBuilder;
use Dune\ErrorHandler\Error;
use Dune\Routing\RouteLoader;
use Dune\Http\Response;
use Symfony\Component\HttpFoundation\Request;


class Kernel
{
    /**
     * \Dune\Core\App instance
     *
     * @var App
     */
    private App $app;
    /**
    * calling the constructer of parent class
    * (\Symfony\Component\HttpKernel\HttpKernel)
    *
    */
    public function __construct(App $app)
    {
        $this->app = $app;
    }
    /**
     * handle the http request
     *
     * @param Request $request
     * @param int $type
     * @param bool $catch
     *
     * @return Response
     */
    public function handle(Request $request)
    {
        $this->setErrorHanlers();

        $this->app->setContainer($this->getContainer());
        require PATH.'/routes/web.php';
        $router = new RouteLoader();
        $router = $router->load();
        $value = $router->dispatch($request->getRequestUri(), $request->getMethod());
        return $this->sendResponse($value);
    }
    /**
     * setting the exception handler &&
     * setting the error ErrorHandler
     * handler \Dune\ErroeHandler\Error
     */
     public function setErrorHanlers(): void
     {
         set_error_handler([Error::class,'handle'], E_ALL);
         set_exception_handler([Error::class,'handleException']);
     }
    /**
     * php di container instance creating
     * \DI\Container
     *
     * @return Container
     */
     public function getContainer(): Container
     {
         $container = new ContainerBuilder();
         if(!$this->app->isLocal()) {
          $container->enableCompilation(PATH.'/storage/cache/container');
          }
          return $container->build();
     }
    /**
     * sending the response to the client
     *
     * @param ?string $response
     *
     * @return mixed
     */
     public function sendResponse(?string $response): mixed
     {
         if(is_string($response)) {
         return (new Response)->text($response);
         }
         return null;
     }
}
