<?php

declare(strict_types=1);

namespace Dune;

use Dune\App;
use DI\Container;
use Dune\Http\ResponseInterface;
use DI\ContainerBuilder;
use Dune\ErrorHandler\Error;
use Dune\Routing\RouteLoader;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Response as BaseResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class Kernel extends HttpKernel
{
    /**
     * \Dune\App instance
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
        parent::__construct(new EventDispatcher(), new ControllerResolver());
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
    public function handle(Request $request, int $type = HttpKernelInterface::MAIN_REQUEST, bool $catch = true): BaseResponse
    {
        $this->setErrorHanlers();

        $this->app->setContainer($this->getContainer());
        $this->app->load();

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
         $builder = new ContainerBuilder();
         return $builder->build();
     }
    /**
     * sending the response to the client
     *
     * @param mixed $response
     *
     * @return Response
     */
     public function sendResponse(mixed $response): BaseResponse
     {
         if($response instanceof ResponseInterface) {
             return $response->response;
         }
         return new BaseResponse($response);
     }
}
