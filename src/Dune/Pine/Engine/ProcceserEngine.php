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

namespace Dune\Pine\Engine;

use Dune\Pine\Engine\AbstractEngine;
use Dune\Pine\Exception\RuntimeError as RuntimeException;

class ProcceserEngine extends AbstractEngine
{
    /**
     * compile if cache mode is false or cached file never exist while cachemode is on
     *
     * @param  string  $view
     * @param array<string,mixed> $data
     *
     * @return string|null|bool
     */
    public function load(string $view, array $data = []): string|null|bool
    {
        if($this->mapper->cacheMode() && !$this->mapper->getCacheFile($view)) {

            $view = $this->compile($view);
            return $this->execute($view, $data);
        }
        if(!$this->mapper->cacheMode()) {
            $view = $this->compile($view);
            return $this->execute($view, $data);
        }
        if($this->mapper->cacheMode() && $this->mapper->getCacheFile($view)) {
            $view = file_get_contents($this->mapper->getCacheFile($view));
            return $this->execute($view, $data);
        }
        return null;
    }
    /**
     *
     * @param  string  $view
     * @param array<string,mixed> $data
     *
     * @throw \Throwable
     *
     * @return string|null|bool
     */
    public function execute(string $view, array $data = []): string|null|bool
    {
        ob_start();
        $outputLevel = ob_get_level();
        try {
            extract($data, EXTR_OVERWRITE);
            if(is_file($view)) {
              require $view;
              return true;
            }
            eval(" ?>" . $view . "<?php ");
        } catch (\Throwable $e) {
            while (ob_get_level() >= $outputLevel) {
                ob_end_clean();
            }
            throw $e;
        }
        return ob_get_clean();
    }
}