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

use Dune\Pine\Parser\PhpError;
use Dune\Pine\Parser\PineError;
use Dune\Pine\Parser\ParserInterface;

class Parser implements ParserInterface
{
    /**
     * \Dune\Pine\Parser\PhpParser instance
     *
     * @var PhpParser
     */
    private PhpParser $phpParser;
    /**
     * \Dune\Pine\Parser\PineParser instance
     *
     * @var PineParser
     */
    private PineParser $pineParser;
    /**
     * initializing \Dune\Pine\Parser\PhpParser and \Dune\Pine\Parser\PineParser instances
     */
    public function __construct(PhpParser $phpParser, PineParser $pineParser)
    {
        $this->phpParser = $phpParser;
        $this->pineParser = $pineParser;
    }
     /**
      * checks php syntax error and pine syntax error
      *
      * param string $template
      *
      * @return bool
      */
    public function check(string $template): bool
    {
        return $this->phpParser->check($template);
        //$this->pineError->check($template);

    }
}
