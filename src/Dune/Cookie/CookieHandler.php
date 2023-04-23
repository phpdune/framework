<?php

declare(strict_types=1);

namespace Dune\Cookie;

class CookieHandler
{
    /**
     * cookie pattern regex
     *
     * @var const
     */
    protected const COOKIE_PATTERN = "^[a-zA-Z0-9_\.]{1,64}$^";

    /**
     * cookie setting handler
     *
     * @param string $key
     * @param string $value
     *
     */
    public function setCookie(string $key, string $value): void
    {
        if ($this->validName($key)) {
            setcookie($key, $value, time() + config('cookie.time'), config('cookie.path'), config('cookie.domain'), config('cookie.secure'), config('cookie.http_only'));
        }
    }
       /**
       * get the cookie handler
       *
       * @param string $key
       *
       * @return string|null
       */
       public function getCookie(string $key): mixed
       {
           return (isset($_COOKIE[$key]) ? $_COOKIE[$key] : null);
       }
       /**
        * cookie unsetting handler
        *
        * @param string $key
        *
        */
       public function unsetCookie(string $key): void
       {
           if ($this->hasCookie($key)) {
               $params = session_get_cookie_params();
               setcookie(
                   $key,
                   '',
                   time() - 3600,
                   $params['path'],
                   $params['domain'],
                   $params['secure'],
                   $params['httponly']
               );
               unset($_COOKIE[$key]);
           }
       }

       /**
              * checking cookie available or not
              *
              * @param string $key
              *
              * @return bool
              */
       public function hasCookie(string $key): bool
       {
           return (isset($_COOKIE[$key]) ? true : false);
       }
       /**
        * delete all cookies that are currently active
        *
        */
       public function flushCookie(): void
       {
           foreach ($_COOKIE as $key => $value) {
               $this->unsetCookie($key);
           }
       }
       /**
        * show all the cookies that are currently active
        *
        *
        * @return null|array<string,string>
        */
       public function allCookie(): ?array
       {
           return $_COOKIE;
       }
       /**
        * Check cookie name is a valid one by regex
        *
        * @param  string  $key
        *
        * @return bool
        */
       public function validName(string $key): bool
       {
           return (preg_match(self::COOKIE_PATTERN, $key) === 1);
       }
}
