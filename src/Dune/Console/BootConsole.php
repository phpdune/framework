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

namespace Dune\Console;

use Symfony\Component\Console\Application;
use Dune\Console\ConsoleInterface;
use Dune\App;
use Dune\Console\BerryCommands;

class BootConsole extends BerryCommands implements ConsoleInterface
{
    /**
     * \Symfony\Component\Console\Application instance
     *
     * @var Application
     */
    private Application $console;
    /**
     * initializing the Application instance
     */
    public function __construct(Application $console)
    {
        $this->console = $console;

    }
     /**
      * console commands registering
      */
    private function register(): void
    {
        foreach ($this->commands as $command) {
            $this->console->add(new $command());
        }
    }
     /**
      * loading the configuration and console command registration
      * running
      *
      * @return int
      */
    public function run(): int
    {
        $this->configure();
        $this->register();
        return $this->console->run();
    }
     /**
      * setting console app details
      */
    private function configure(): void
    {
        $this->console->setName('Dune Framework');
        $this->console->setVersion(App::VERSION);
    }
}
