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
    protected const COOKIE_PATTERN = '^[a-zA-Z0-9_\.]{1,64}$^';

    /**
     * cookie setting handler
     *
     * @param string $key
     * @param mixed $value
     *
     * @return none
     */
    protected static function setCookie(string $key, mixed $value): void
    {
        if (self::validName($key)) {
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
      protected static function getCookie(string $key): mixed
      {
          return (isset($_COOKIE[$key]) ? $_COOKIE[$key] : null);
      }
      /**
       * cookie unsetting handler
       *
       * @param string $key
       *
       * @return none
       */
      protected static function unsetCookie(string $key): void
      {
          if (self::hasCookie($key)) {
              setcookie($key, '', time() - 3600);
          }
      }
      /**
       * checking cookie available or not
       *
       * @param string $key
       *
       * @return bool
       */
      protected static function hasCookie(string $key): bool
      {
          return (isset($_COOKIE[$key]) ? true : false);
      }
      /**
       * delete all cookies that are currently active
       *
       * @param none
       *
       * @return none
       */
      protected static function flushCookie(): void
      {
          foreach($_COOKIE as $key => $value) {
            self::unsetCookie($key);
          }
      }
      /**
       * show all the cookies that are currently active
       *
       * @param none
       *
       * @return null|array
       */
      protected static function allCookie(): ?array
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
      protected static function validName(string $key): bool
      {
          return (preg_match(self::COOKIE_PATTERN, $key) === 1);
      }
}
