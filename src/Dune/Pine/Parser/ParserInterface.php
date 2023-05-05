<?php


declare(strict_types=1);

namespace Dune\Pine\Parser;

interface ParserInterface
{
    /**
     * syntax error checker
     *
     * @return bool
     */
    public function check(string $template): bool;
}
