<?php

declare(strict_types=1);

namespace Dune\Csrf;

use Dune\Csrf\CsrfContainer;

class Csrf implements CsrfInterface
{
    use CsrfContainer;
    /**
     * \Dune\Csrf\CsrfHandler instance
     *
     * @var CsrfHandler
     */
    private static ?CsrfHandler $handler = null;
    /**
     * @param  none
     *
     * @return null|string
     */
    public static function generate(): ?string
    {
        self::init();
        return self::$handler->setCsrfToken();
    }
     /**
      * @param  none
      *
      * @return null|string
      */
    public static function get(): ?string
    {
         self::init();
        return self::$handler->getCurrentToken();
    }
     /**
      * @param  none
      *
      * @return null|string
      */
    public static function reGenerate(): ?string
    {
        self::init();
        return self::$handler->tokenRegenerate();
    }
     /**
      * @param  string $token
      * @param string $id
      *
      * @return bool
      */
  public static function validate(string|null $token, string|null $id): bool
  {
      self::init();
      return self::$handler->tokenValidate($token, $id);
  }
}
