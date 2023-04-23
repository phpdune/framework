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
     * @return array<mixed>
     */
    public function all(): array;
    /**
    * will return request method
    *
    * @return string
    */
    public function method(): string;
    /**
    * will return server values
    *
    * @return string $key
    */
    public function server(string $key): ?string;
    /**
    * return true if request method is GET
    *
    * @return bool
    */
    public function isGet(): bool;
    /**
    * return true if request method is POST
    *
    * @return bool
    */
    public function isPost(): bool;
    /**
    * return all headers
    *
    * @return string|bool|array<string,mixed>
    */
    public function getHeaders(string $uri): array|bool|string;
    /**
    * @return string
    */
    public function getIp(): string;
}
