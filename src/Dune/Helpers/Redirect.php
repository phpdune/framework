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
     * @param string $key
     *
     * @return self|null|Redirect
     */
    public function route(string $key): self|null|Redirect
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
       * @param string $key
       * @param mixed $value
       *
       * @return self
       */
    public function with(string $key, mixed $value): self
    {
        Session::set('__'.$key, $value);
        return $this;
    }
      /**
       * can access this value through session
       *
       * @param array<string,mixed> $data
       *
       * @return self
       */
    public function withArray(array $data): self
    {
        foreach ($data as $key => $value) {
            Session::set('__'.$key, $value);
        }
        return $this;
    }
      /**
       * redirection
       */
    private function redirect(): void
    {
        header("Location: {$this->uri}");
    }
}
