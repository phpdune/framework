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

namespace Dune\Pine;

interface ViewInterface
{
    /**
     * Check the view file exist else throw an exception
     *
     * @param  string  $view
     * @param  array<string,mixed> $data
     *
     * @throw \NotFound
     *
     * @return string|null|bool
     */
    public function render(string $view, array $data = []): string|null|bool;
}
