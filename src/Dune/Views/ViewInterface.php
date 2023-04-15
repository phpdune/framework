<?php

declare(strict_types=1);

namespace Dune\Views;

interface ViewInterface
{
    /**
     * @param none
     *
     * @return none
     */
    public function __construct();

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
    public function render(string $view, array $data = []): ?string;
}
