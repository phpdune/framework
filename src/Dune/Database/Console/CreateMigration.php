<?php

declare(strict_types=1);

namespace Dune\Database\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateMigration extends Command
{
    /**
     * command name
     *
     * @var string
     */

    protected static $defaultName = 'create:migration';

    /**
     * default symfony console configure method
     * setting description and arguments
     *
     */
    protected function configure(): void
    {
        $this
        ->setDescription('Create a migration file')
        ->addArgument('name', InputArgument::REQUIRED, 'table name');
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
        $name = $this->getPrefixName(
            $input->getArgument('name')
        );
        $message = new SymfonyStyle($input, $output);
        if(!$this->migrationExist($name)) {
            $stub = $this->getStub($name);
            $file = fopen("app/database/migration/" . $name . ".php", "w");
            fwrite($file, $stub);
            fclose($file);
            $message->success(sprintf('%s Created Successfully', $name));
            return Command::SUCCESS;
        }
        $message->error(sprintf('%s Already Exists', $name));
        return Command::FAILURE;
    }
    /**
     * check the controller exists or not
     *
     * @param string $name
     *
     * @return bool
     */
    protected function migrationExist(string $name): bool
    {
        return file_exists(PATH . "/app/database/migration/" . $name . ".php");
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
        $stub = PATH . "/vendor/dune/framework/src/Dune/Stubs/migration.stub";
        $name = str_replace('migrate_', '', $name);
        $stub = file_get_contents($stub);
        $stub = str_replace("{{ Table }}", $name, $stub);
        return $stub;
    }
    /**
     * check the controller name ends with 'Controller' , if not then return controller name + Controller
     *
     * @param string $name
     *
     * @return string
     */
    protected function getPrefixName(string $name): string
    {
        if(str_starts_with($name, 'migrate_')) {
            return $name;
        }
        return 'migrate_'.$name;
    }
}
