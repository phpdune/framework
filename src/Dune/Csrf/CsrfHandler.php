<?php

declare(strict_types=1);

namespace Dune\Csrf;

use Dune\Session\Session;
use Dune\Http\Request;

class CsrfHandler
{
    protected static function setCsrfToken(): ?string
    {
        $key = bin2hex(random_bytes(32));
        $token = hash_hmac("sha256", base64_encode($key), $key);
        Session::has("_token")
            ? Session::overwrite("_token", $token)
            : Session::set("_token", $token);
        return $token;
    }
    protected static function getCurrentToken(): ?string
    {
        return Session::has("_token")
            ? Session::get("_token")
            : self::setCsrfToken();
    }
    protected static function tokenRegenerate(): ?string
    {
        return self::setCsrfToken();
    }
    protected static function tokenValidate(string|null $token, string|null $id): bool
    {
        $request = new Request();
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
