<?php

declare(strict_types=1);

namespace Dune\Csrf;

class Csrf extends CsrfHandler implements CsrfInterface 
{
    /**
     * @param  none
     *
     * @return null|string
     */
   public static function generate(): ?string
   {
      return self::setCsrfToken();
   }
    /**
     * @param  none
     *
     * @return null|string
     */
   public static function get(): ?string
   {
     return self::getCurrentToken();
   }
    /**
     * @param  none
     *
     * @return null|string
     */
   public static function reGenerate(): ?string
   {
     return self::tokenRegenerate();
   }
    /**
     * @param  string $token
     * @param string $id
     *
     * @return bool
     */
  public static function validate(string|null $token, string|null $id): bool 
  {
    return self::tokenValidate($token,$id);
  }
  
}