<?php

declare(strict_types=1);

namespace Dune\Views;

interface ViewInterface
{
    /**
     * Check the view file exist else throw an exception
     *
     * @param  string  $view
     * @param  array  $data
     *
     * @throw \NotFound
     *
     * @return string|null
     */
    public static function render(string $view, array $data = []): ?string;
}
