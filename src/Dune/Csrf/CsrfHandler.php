<?php

declare(strict_types=1);

namespace Dune\Csrf;

use Dune\Session\Session;
use Dune\Http\Request;

class CsrfHandler
{
    /**
     * set the csrf token
     *
     * @return null|string
     */
    public function setCsrfToken(): ?string
    {
        $key = bin2hex(random_bytes(32));
        $token = hash_hmac("sha256", base64_encode($key), $key);
        Session::has("_token")
            ? Session::overwrite("_token", $token)
            : Session::set("_token", $token);
        return $token;
    }
     /**
     * get the current csrf token
     *
     * @return null|string
     */
    public function getCurrentToken(): ?string
    {
        return Session::has("_token")
            ? Session::get("_token")
            : $this->setCsrfToken();
    }
    /**
     * regenerate new csrf token
     *
     * @return null|string
     */
    public function tokenRegenerate(): ?string
    {
        return $this->setCsrfToken();
    }
    /**
     * validating csrf token
     *
     * @param  string|null $token
     * @param string|null $id
     *
     * @return bool
     */
    public function tokenValidate(string|null $token, string|null $id): bool
    {
        if (!$token) {
            return false;
        } elseif (!$id) {
            return false;
        } elseif (hash_equals($token, $id)
        ) {
            return true;
        }
        return false;
    }
}
