<?php

declare(strict_types=1);

namespace Dune\Console;

use Symfony\Component\Console\Application;
use Dune\Console\CreateController;
use Dune\Console\CreateMiddleware;
use Dune\Console\CreateValidation;
use Dune\Console\ConsoleInterface;
use Dune\App;

class BootConsole implements ConsoleInterface
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
        $this->console->add(new CreateController());
        $this->console->add(new CreateMiddleware());
        $this->console->add(new CreateValidation());
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
