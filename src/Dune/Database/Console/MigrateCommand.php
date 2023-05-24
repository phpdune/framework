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

class MigrateCommand extends Command
{
    /**
     * \Symfony\Component\Console\Style\SymfonyStyle instance
     *
     * @var SymfonyStyle
     */
    protected SymfonyStyle $msg;

    /**
     * command name
     *
     * @var string
     */
    protected static $defaultName = "migrate";

    /**
     * default symfony console configure method
     * setting description and arguments
     *
     */
    protected function configure(): void
    {
        $this->setDescription("Perform databse migration");
    }
    /**
     * Database table migration
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->msg = new SymfonyStyle($input, $output);
        $files = glob(PATH.'/app/database/migration/*.php');
        foreach ($files as $file) {
            $file = $this->parse($file);
            $this->migrate($file);
        }
        return Command::SUCCESS;
    }
    /**
     * remove the extension and adding the namespace
     *
     * @param string $fileName
     *
     * @return string
     */
    private function parse(string $fileName): string
    {
        $fileName = basename($fileName);
        $parsed = str_replace('.php', '', $fileName);
        $finalFile = '\App\Database\Migration\\'.$parsed;
        return $finalFile;
    }
    /**
     * migrate the database columns
     *
     * @param string $class
     *
     * @return ?SymfonyStyle
     */
    public function migrate(string $class): ?SymfonyStyle
    {
        try {
            $tableName = str_replace('\App\Database\Migration\migrate_', '', $class);
            $class = new $class();
            $class->up();
            $this->msg->info('migrated table '.$tableName);
        } catch(\Exception $e) {
            $this->handleException($e);
        }
        return null;
    }
    /**
     * handle the database exception and giving corresponding response
     *
     * @param \Exception $e
     *
     * @return ?SymfonyStyle
     */
    public function handleException(\Exception $e): ?SymfonyStyle
    {
        if($e->getCode() == 2002) {
            return $this->msg->error('Database Connection refused');
        }
        if($e->getCode() == '42S01') {
            return $this->msg->note('Alredy migrated');
        }
        return null;
    }
}
