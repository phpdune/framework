<?php

declare(strict_types=1);

namespace Dune\Csrf;

interface CsrfInterface
{
    /**
     * get the csrf token
     *
     * @return null|string
     */
    public static function get(): ?string;
    /**
      * generate a csrf token
      *
      * @return null|string
      */
    public static function generate(): ?string;
    /**
     * regenerate csrf token
     *
     * @return null|string
     */
    public static function reGenerate(): ?string;
    /**
     * validate the token by form and session
     *
     * @param  string $token
     * @param string $id
     *
     * @return bool
     */
    public static function validate(string $token, string $id): bool;
}
