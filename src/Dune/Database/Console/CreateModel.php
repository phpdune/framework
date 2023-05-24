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

namespace Dune\Database\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateModel extends Command
{
    /**
     * command name
     *
     * @var string
     */

    protected static $defaultName = 'create:model';

    /**
     * default symfony console configure method
     * setting description and arguments
     *
     */
    protected function configure(): void
    {
        $this
        ->setDescription('Create a model file')
        ->addArgument('name', InputArgument::REQUIRED, 'model name');
    }
    /**
     * main execute function
     * create controller by given name (argument)
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $message = new SymfonyStyle($input, $output);
        if(!$this->modelExist($name)) {
            $stub = $this->getStub($name);
            $file = fopen("app/database/model/" . $name . ".php", "w");
            fwrite($file, $stub);
            fclose($file);
            $message->success(sprintf('%s Model Created Successfully', $name));
            return Command::SUCCESS;
        }
        $message->error(sprintf('%s Model Already Exists', $name));
        return Command::FAILURE;
    }
    /**
     * check the controller exists or not
     *
     * @param string $name
     *
     * @return bool
     */
    protected function modelExist(string $name): bool
    {
        return file_exists(PATH . "/app/database/model/" . $name . ".php");
    }
    /**
     * return the controller stub file
     *
     * @param string $name
     *
     * @return string
     */
    protected function getStub(string $name): string
    {
        $stub = PATH . "/vendor/dune/framework/src/Dune/Stubs/model.stub";
        $stub = file_get_contents($stub);
        $stub = str_replace("{{ Model }}", $name, $stub);
        return $stub;
    }

}
