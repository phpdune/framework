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

namespace Dune\Http;

use Dune\Http\ResponseInterface;
use Leaf\Http\Response as BaseResponse;

class Response extends BaseResponse implements ResponseInterface
{

    /**
     * text response sending
     *
     * @param array $text
     * @param int $code
     *
     * @return null
     */
    public function text(string $text, int $code = 200): null
    {
        return $this->markup($text,$code);
    }

}
