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

namespace Dune\Session;

use Dune\Session\SessionContainer;
use Dune\Session\Session;
use Dune\Session\Config\SessionConfig;
use Dune\Session\SessionEncrypter;

class SessionLoader
{
    use SessionContainer;
    /**
     * \Dune\Routing\Router instance
     *
     * @var ?Router
     */
    protected ?Session $session = null;
    /**
     * calling router method
     * setting up router instance
     *
     */
    public function __construct(array $configs)
    {
        $this->__setUp();
        $config = new SessionConfig($configs);
        if(!$this->session) {
            $this->session = new Session(
                new SessionEncrypter(),
                $config
            );
        }
    }
      /**
       * returning the loaded router instance
       *
       * @return Session
       */
    public function load(): Session
    {
        return $this->session;
    }
}
