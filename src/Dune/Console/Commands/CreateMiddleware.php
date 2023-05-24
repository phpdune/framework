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

namespace Dune\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateMiddleware extends Command
{
    /**
     * command name
     *
     * @var string
     */

    protected static $defaultName = 'create:middleware';

    /**
     * default symfony console configure method
     * setting description and arguments
     *
     */
    protected function configure(): void
    {
        $this
        ->setDescription('Create a middleware file')
        ->addArgument('name', InputArgument::REQUIRED, 'Middleware name');
    }
    /**
     * main execute function
     * create middleware by given name (argument)
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $this->getPrefixName(
            $input->getArgument('name')
        );
        $message = new SymfonyStyle($input, $output);
        if(!$this->middlewareExist($name)) {
            $stub = $this->getStub($name);
            $file = fopen("app/middleware/" . $name . ".php", "w");
            fwrite($file, $stub);
            fclose($file);
            $message->success(sprintf('%s Created Successfully', $name));
            return Command::SUCCESS;
        }
        $message->error(sprintf('%s Already Exists', $name));
        return Command::FAILURE;
    }
    /**
     * check the middleware exists or not
     *
     * @param string $name
     *
     * @return bool
     */
    protected function middlewareExist(string $name): bool
    {
        return file_exists(PATH . "/app/middleware/" . $name . ".php");
    }
    /**
     * return the middleware stub file
     *
     * @param string $name
     *
     * @return string
     */
    protected function getStub(string $name): string
    {
        $stub = PATH . "/vendor/dune/framework/src/Dune/Stubs/middleware.stub";
        $stub = file_get_contents($stub);
        $stub = str_replace("{{ Middleware }}", $name, $stub);
        return $stub;
    }
    /**
     * check the middleware name ends with 'Middleware' , if not then return middleware name + Middleware
     *
     * @param string $name
     *
     * @return string
     */
    protected function getPrefixName(string $name): string
    {
        if(!str_ends_with($name, 'Middleware')) {
            return $name.'Middleware';
        }
        return $name;
    }
}
