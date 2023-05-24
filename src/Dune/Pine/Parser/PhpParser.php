<?php

/*
 * This file is part of Dune Framework.
 *
 * (c) Abhishek B <phpdune@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


declare(strict_types=1);

namespace Dune\Pine\Parser;

use PhpParser\Error;
use PhpParser\ParserFactory;
use PhpParser\Parser\Multiple;
use Dune\Pine\Parser\ParserInterface;
use Dune\Pine\Exception\SyntaxError;

class PhpParser implements ParserInterface
{
    /**
     * \PhpParser\Parser\Multiple instance
     *
     * @var Multiple
     */
    protected Multiple $parser;
    /**
     * initializing \PhpParser\ParserFactory instance
     */
    public function __construct()
    {
        $this->parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
    }
      /**
       * checks php syntax error
       *
       * @throw \Dune\Pine\Exception\SyntaxError
       *
       * param string $template
       *
       * @return bool
       */
    public function check(string $template): bool
    {
        try {
            $this->parser->parse($template);
            return true;
        } catch (Error $e) {
            throw new SyntaxError($e->getMessage());
        }
    }
}
