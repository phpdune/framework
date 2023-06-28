<?php

/*
 * This file is part of Dune Framework.
 *
 * (c) Abhishek B <phpdune@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dune\Http\Middleware;

use Dune\Http\Request;
use Closure;

interface MiddlewareInterface
{
    public function handle(Request $request, Closure $next): Request;
}
