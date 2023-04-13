<?php

declare(strict_types=1);

namespace Dune\Http;

interface RequestInterface
{
    /**
     * will return the value from key
     *
     * @param string $key
     * @param null $default
     *
     * @return string|null
     */
    public function get($key, $default = null): ?string;
    /**
     * will return everything from $this->data
     *
     * @param  none
     *
     * @return array
     */
    public function all(): array;
    /**
    * will return request method
    *
    * @param  none
    *
    * @return string
    */
    public function method(): string;
    /**
    * will return server values
    *
    * @param  none
    *
    * @return string
    */
    public function server($key): ?string;
    /**
    * return true if request method is GET
    *
    * @param  none
    *
    * @return bool
    */
    public function isGet(): bool;
    /**
    * return true if request method is POST
    *
    * @param  none
    *
    * @return bool
    */
    public function isPost(): bool;
    /**
    * return all headers
    *
    * @param  none
    *
    * @return string
    */
    public function getHeaders(string $uri): string;
    /**
    * @param  none
    *
    * @return string
    */
    public function getIp(): string;
}
