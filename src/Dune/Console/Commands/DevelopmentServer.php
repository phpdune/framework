<?php

declare(strict_types=1);

namespace Dune\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputOption;

class DevelopmentServer extends Command
{
    /**
     * command name
     *
     * @var string
     */
    protected static $defaultName = 'serve';

    /**
     * default symfony console configure method
     * setting description and arguments
     *
     */
    protected function configure(): void
    {
        $this
        ->setDescription('Start php\'s default development server')
        ->addOption('host', null, InputOption::VALUE_OPTIONAL, 'server host', '127.0.0.1')
        ->addOption('port', null, InputOption::VALUE_OPTIONAL, 'server port', '8000');
    }
    /**
     * clear view cache
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $message = new SymfonyStyle($input, $output);
        $host = $input->getOption('host');
        $port = $input->getOption('port');
        $command = "php -S $host:$port -t public";
        $message->info('Development server started');
        shell_exec($command);
        return Command::SUCCESS;
    }
}
