<?php

declare(strict_types=1);

namespace Dune\Helpers;

use Dune\Routing\RouteHandler;
use Dune\ErrorHandler\Error;
use Dune\Session\Session;

class Redirect
{
    /**
     * redirect uri
     *
     * @var string
     */
    private string $uri;
    /**
     * getting route uri from its name
     *
     * @param  string  $key
     *
     * @return self
     */
    public function route(string $key): self
    {
        $array = RouteHandler::$names;
        if (array_key_exists($key, $array)) {
            $routeUri = RouteHandler::$names[$key];
            $this->uri = $routeUri;
            $this->redirect();
            return $this;
        }
        return null;
    }
      /**
       * will redirect to the back page
       *
       * @param  none
       *
       * @return self
       */
    public function back(): self
    {
        $this->uri = $_SERVER['HTTP_REFERER'] ?? null;
        $this->redirect();
        return $this;
    }
      /**
       * can access this value through session
       *
       * @param  string  $key
       * @param mixed $value
       *
       * @return none
       */
    public function with(string $key, mixed $value): void
    {
        Session::set('__'.$key, $value);
    }
      /**
       * can access this value through session
       *
       * @param  array  $data
       *
       * @return none
       */
    public function withArray(array $data): void
    {
        foreach($data as $key => $value) {
        Session::set('__'.$key, $value);
        }
    }
      /**
       * redirection
       *
       * @param  none
       *
       * @return none
       */
    private function redirect(): void
    {
        header("Location: {$this->uri}");
    }
}
