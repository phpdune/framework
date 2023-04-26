<?php

declare(strict_types=1);

namespace Dune\Http;

use Dune\Helpers\Redirect;
use Dune\Http\Validater;
use Dune\Http\ValidaterInterface;

class Request implements RequestInterface
{
    use RequestContainer;
    /**
     * validater instance
     *
     * @var Validater
     */
    private ?Validater $validater = null;

    /**
      * All data from $_REQUEST and $_SERVER
      *
      * @var array<string,mixed>
      */
    private array $data = [];

    /**
      * route params
      *
      * @var array<string,mixed>
      */
    public array $params = [];
    /**
     * All data from $_GET and $_POST will stored from this constructer
     *
     */
    public function __construct()
    {
        $this->init();
        if ($this->isGet()) {
            foreach ($_GET as $key => $value) {
                $this->data[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->isPost()) {
            foreach ($_POST as $key => $value) {
                $this->data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
    }
    /**
     * @param string $key
     * @param null $default
     *
     * @return string|null
     */
    public function get($key, $default = null): ?string
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }
    /**
     *
     * @return array<mixed>
     */
    public function all(): array
    {
        return $this->data;
    }
     /**
     * @return string
     */
    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

     /**
     *
     * @return string $key
     */
     public function server(string $key): ?string
     {
         $value = $_SERVER[strtoupper($key)] ?? null;
         return $value;
     }
     /**
     *
     * @return bool
     */
     public function isGet(): bool
     {
         if ($this->method() == 'GET') {
             return true;
         }
         return false;
     }
    /**
     *
     * @return bool
     */
     public function isPost(): bool
     {
         if ($this->method() == 'POST') {
             return true;
         }
         return false;
     }
     /**
     * @return array<string,mixed>|bool|string
     */
     public function getHeaders(string $uri): array|bool|string
     {
         return get_headers($uri);
     }
     /**
     * @return string
     */
     public function getIp(): string
     {
         return $this->server('remote_addr');
     }
    /**
     * @param  ValidaterInterface|array<mixed> $data
     *
     * @return ?Redirect
     */
     public function validate(array|ValidaterInterface $data): ?Redirect
     {
         return $this->validater->validate($data);
     }

    /**
     * @param array<string,mixed> $params
     *
     */
     public function setParams(array $params): void
     {
         $this->params = $params;
     }
    /**
     * @param  string $key
     *
     */
     public function param(string $key): mixed
     {
         return (isset($this->params[$key]) ? $this->params[$key] : null);
     }
}
