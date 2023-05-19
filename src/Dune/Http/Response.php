<?php

declare(strict_types=1);

namespace Dune\Http;

use Symfony\Component\HttpFoundation\Response as BaseResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Dune\Http\ResponseInterface;

class Response implements ResponseInterface
{
    /**
     * \Symfony\Component\HttpFoundation\Response
     *
     * @var BaseResponse
     */
    public BaseResponse $response;

    /**
     * Initializing var $response
     *
     * @param BaseResponse $response
     */
    public function __construct(BaseResponse $response)
    {
        $this->response = $response;
    }
    /**
     * json response sending
     *
     * @param array<mixed,mixed> $jsonData
     * @param int $code
     *
     * @return self
     */
    public function json(array $jsonData, int $code = 200): self
    {
        $jsonData = json_encode($jsonData);
        $this->response->setContent($jsonData);
        $this->response->setStatusCode($code);
        $this->response->headers->set("Content-Type", "application/json");
        $this->sendResponse();
        return $this;
    }
    /**
     * text response sending
     *
     * @param array $text
     * @param int $code
     *
     * @return self
     */
    public function text(string $text, int $code = 200): self
    {
        $this->response->setContent($text);
        $this->response->setStatusCode($code);
        $this->sendResponse();
        return $this;
    }
    /**
     * set the response headers
     *
     * @param array<string,string> $headers
     *
     * @return self
     */
    public function withHeader(array $headers = []): self
    {
        foreach ($headers as $key => $value) {
            $this->response->headers->set($key, $value);
        }
        return $this;
    }
    /**
     * set the response status code
     *
     * @param int $code
     *
     * @return self
     */
    public function withStatus(int $code = 200): self
    {
        $this->response->setStatusCode($code);
        return $this;
    }
    /**
     * send the response
     *
     * @return BaseResponse
     */
    private function sendResponse(): BaseResponse
    {
        return $this->response;
    }
    /**
     * access the method of symfony response
     *
     * @param string $method
     * @param array $args
     *
     * @return mixed
     */
     public function __call(string $method, array $args): mixed
     {
         return $this->response->$method(...$args);
     }
}
