<?php

declare(strict_types=1);

namespace Dune\Csrf;

use Dune\Http\Request;
use Dune\Csrf\CsrfInterface;
use Dune\Csrf\CsrfContainer;

class Csrf implements CsrfInterface
{
    use CsrfContainer;

    /**
     * container instance
     */
    public function __construct()
    {
        $this->__setUp();
    }
    /**
     * set the csrf token
     *
     * @return null|string
     */
    public function generate(): ?string
    {
        $request = $this->container->get(Request::class);
        $key = bin2hex(random_bytes(32));
        $token = hash_hmac("sha256", base64_encode($key), $key);
        $request->session()->has('_token')
        ? $request->session()->overwrite("_token", $token)
        : $request->session()->set("_token", $token);
        return $token;
    }
    /**
     * regenerate new csrf token
     *
     * @return null|string
     */
    public function reGenerate(): ?string
    {
        return $this->generate();
    }
}
