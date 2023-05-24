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

namespace Dune\Csrf;

interface CsrfInterface
{
    /**
     * set the csrf token
     *
     * @return null|string
     */
    public function generate(): ?string;
    /**
     * regenerate csrf token
     *
     * @return null|string
     */
    public function reGenerate(): ?string;
}
