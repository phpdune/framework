<?php

namespace Dune\Http;

class Request implements RequestInterface
{
    /**
      * All data from $_REQUEST and $_SERVER
      *
      * @var array
      */
    public array $data = [];

    /**
     * All data from $_GET and $_POST will stored from this constructer
     *
     * @param  none
     *
     * @return none
     */
    public function __construct()
    {
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
     * @param  none
     *
     * @return array
     */
    public function all(): array
    {
        return $this->data;
    }
     /**
     * @param  none
     *
     * @return string
     */
    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

     /**
     * @param  none
     *
     * @return string
     */
     public function server($key): ?string
     {
         $value = $_SERVER[strtoupper($key)] ?? null;
         return $value;
     }
     /**
     * @param  none
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
     * @param  none
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
     * @param  none
     *
     * @return string
     */
     public function getHeaders(): string
     {
         return get_headers();
     }
     /**
     * @param  none
     *
     * @return string
     */
     public function getIp(): string
     {
         return $this->server('remote_addr');
     }
}
